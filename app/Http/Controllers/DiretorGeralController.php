<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForwarding;
use App\Models\ApplicationRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DiretorGeralController extends Controller
{


    public function dashboard()
    {
        $userId = Auth::id();

        $forwardings = RequestForwarding::where('receiver_id', $userId)
            ->whereIn('status', ['encaminhado', 'devolvido'])
            ->with(['requerimento', 'sender'])
            ->latest()
            ->get();

        $roleName    = Auth::user()->role;
        $cargoSlug   = Auth::user()->getRouteSlug();
        $routePrefix = 'diretor-geral';

        return view('diretor-geral.dashboard', compact('forwardings', 'roleName', 'cargoSlug', 'routePrefix'));
    }


    public function dynamicDashboard(string $cargoSlug)
    {
        $user = Auth::user();

        if ($cargoSlug !== $user->getRouteSlug()) {
            abort(403, 'Você não tem acesso a este painel.');
        }

        $forwardings = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('status', ['encaminhado', 'devolvido'])
            ->with(['requerimento', 'sender'])
            ->latest()
            ->get();

        $roleName    = $user->role;
        $routePrefix = 'painel';

        return view('diretor-geral.dashboard', compact('forwardings', 'roleName', 'cargoSlug', 'routePrefix'));
    }

    public function dynamicReports(string $cargoSlug)
    {
        $user = Auth::user();

        if ($cargoSlug !== $user->getRouteSlug()) {
            abort(403, 'Você não tem acesso a este painel.');
        }

        $roleName    = $user->role;
        $routePrefix = 'painel';

        return view('diretor-geral.reports', compact('roleName', 'cargoSlug', 'routePrefix'));
    }

    public function reports()
    {
        return view('diretor-geral.reports');
    }

    public function getStatistics()
    {
        $user = Auth::user();

        $totalProcessados = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('status', ['finalizado', 'indeferido'])
            ->count();

        $totalEncaminhados = RequestForwarding::where('receiver_id', $user->id)->count();

        $totalDevolvidos = RequestForwarding::where('receiver_id', $user->id)
            ->where('status', 'devolvido')
            ->count();

        $estatisticasMensais = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $startOfMonth = $mes->copy()->startOfMonth();
            $endOfMonth   = $mes->copy()->endOfMonth();

            $processados = RequestForwarding::where('receiver_id', $user->id)
                ->whereIn('status', ['finalizado', 'indeferido'])
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();

            $estatisticasMensais[] = [
                'mes'   => $mes->format('M/Y'),
                'total' => $processados,
            ];
        }

        $statusStats = RequestForwarding::where('receiver_id', $user->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'totalProcessados'   => $totalProcessados,
            'totalEncaminhados'  => $totalEncaminhados,
            'totalDevolvidos'    => $totalDevolvidos,
            'estatisticasMensais' => $estatisticasMensais,
            'statusStats'        => $statusStats,
        ]);
    }

    public function generateProcessedReport(Request $request)
    {
        $user   = Auth::user();
        $period = $request->input('period', 'mes-atual');

        [$startDate, $endDate, $periodoTexto] = $this->resolvePeriod($period, $request);

        $data = $this->getDiretorData($user, $startDate, $endDate);

        $pdf = PDF::loadView('diretor-geral.reports.processed-pdf', [
            'user'         => $user,
            'data'         => $data,
            'periodoTexto' => $periodoTexto,
            'dataGeracao'  => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-diretor-geral-processados-' . now()->format('dmY-His') . '.pdf');
    }

    public function generatePeriodReport(Request $request)
    {
        $user       = Auth::user();
        $startDate  = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate    = Carbon::parse($request->input('endDate'))->endOfDay();
        $periodoTexto = 'Período: ' . $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        $data = $this->getDiretorData($user, $startDate, $endDate);

        $pdf = PDF::loadView('diretor-geral.reports.period-pdf', [
            'user'         => $user,
            'data'         => $data,
            'periodoTexto' => $periodoTexto,
            'dataGeracao'  => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-diretor-geral-periodo-' . now()->format('dmY-His') . '.pdf');
    }


    public function processRequest(Request $request, $forwardingId)
    {
        $forwarding   = RequestForwarding::findOrFail($forwardingId);
        $requerimento = $forwarding->requerimento;

        if ($forwarding->receiver_id != Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para processar este requerimento.');
        }

        $forwarding->status = $request->action;

        if ($request->has('resposta') && !empty($request->resposta)) {
            $requerimento->resposta = $request->resposta;
        }

        if ($request->hasFile('anexos')) {
            $anexos = [];
            foreach ($request->file('anexos') as $file) {
                $path     = $file->store('requerimentos_arquivos', 'public');
                $anexos[] = $path;
            }

            $anexosAntigos = $requerimento->anexos_finalizacao
                ? json_decode($requerimento->anexos_finalizacao, true)
                : [];
            $todosAnexos = array_merge($anexosAntigos, $anexos);
            $requerimento->anexos_finalizacao = json_encode($todosAnexos);
        }

        $forwarding->save();

    if (in_array($request->action, ['finalizado', 'indeferido'])) {
            $requerimento->status       = $request->action;
            $requerimento->finalizado_por = Auth::user()->name;
            $requerimento->save();

             $aluno = \App\Models\User::where('cpf', $requerimento->cpf)
            ->orWhere('email', $requerimento->email)
            ->first();

        if ($aluno && $aluno->role === 'Aluno') {
            try {
                $statusText = $request->action === 'finalizado' ? 'Aprovado' : 'Indeferido';
                \App\Models\Notification::create([
                    'user_id' => $aluno->id,
                    'title' => $statusText === 'Aprovado' ? 'Requerimento Deferido ✓' : 'Requerimento Indeferido ✗',
                    'message' => "Seu requerimento #" . $requerimento->id . " foi " . ($statusText === 'Aprovado' ? 'DEFERIDO' : 'INDEFERIDO') . ".",
                    'event_type' => 'status_' . $request->action,
                    'related_id' => $requerimento->id,
                    'is_read' => false
                ]);
                \Illuminate\Support\Facades\Log::info('Notificação pop-up criada com sucesso para aluno (Diretor Geral)', [
                    'user_id' => $aluno->id,
                    'request_id' => $requerimento->id,
                    'status' => $request->action
                ]);
            } catch (\Exception $notificationError) {
                \Illuminate\Support\Facades\Log::error('Erro ao criar notificação pop-up', [
                    'message' => $notificationError->getMessage()
                ]);
            }
        }
    }

        return redirect()->back()->with('success', 'Requerimento processado com sucesso.');
    }

    public function returnRequest(Request $request, $forwardingId)
    {
        $forwarding = RequestForwarding::findOrFail($forwardingId);

        $forwarding->status           = 'devolvido';
        $forwarding->internal_message = $request->input('internal_message');
        $forwarding->is_returned      = true;
        $forwarding->save();

        $requerimento         = $forwarding->requerimento;
        $requerimento->status = 'devolvido';
        $requerimento->save();

        $aluno = \App\Models\User::where('cpf', $requerimento->cpf)
        ->orWhere('email', $requerimento->email)
        ->first();

    if ($aluno && $aluno->role === 'Aluno') {
        try {
            \App\Models\Notification::create([
                'user_id' => $aluno->id,
                'title' => 'Requerimento Devolvido',
                'message' => 'Seu requerimento #' . $requerimento->id . ' foi devolvido  à CRADT. Você será notificado quando houver uma atualização.',
                'is_read' => false,
                'event_type' => 'request_returned',
                'related_id' => $requerimento->id,
            ]);
            \Illuminate\Support\Facades\Log::info('Notificação de devolução criada para aluno', [
                'user_id' => $aluno->id,
                'request_id' => $requerimento->id
            ]);
        } catch (\Exception $notificationError) {
            \Illuminate\Support\Facades\Log::error('Erro ao criar notificação de devolução', [
                'message' => $notificationError->getMessage()
            ]);
        }
    }

        return redirect()->back()->with('success', 'Requerimento devolvido para o CRADT com sucesso');
    }

    private function resolvePeriod(string $period, Request $request): array
    {
        switch ($period) {
            case 'mes-atual':
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                $texto = 'Mês Atual: ' . $start->format('m/Y');
                break;
            case 'mes-anterior':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end   = Carbon::now()->subMonth()->endOfMonth();
                $texto = 'Mês Anterior: ' . $start->format('m/Y');
                break;
            case 'ano-atual':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfYear();
                $texto = 'Ano Atual: ' . $start->format('Y');
                break;
            case 'personalizado':
                $start = Carbon::parse($request->input('startDate'))->startOfDay();
                $end   = Carbon::parse($request->input('endDate'))->endOfDay();
                $texto = 'Período: ' . $start->format('d/m/Y') . ' a ' . $end->format('d/m/Y');
                break;
            default:
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                $texto = 'Mês Atual: ' . $start->format('m/Y');
        }

        return [$start, $end, $texto];
    }

    private function getDiretorData($user, $startDate, $endDate): array
    {
        $data = [];

        $data['requerimentosRecebidos'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data['totalRecebidos'] = $data['requerimentosRecebidos']->count();

        $data['requerimentosProcessados'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('status', ['finalizado', 'indeferido'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $data['totalProcessados'] = $data['requerimentosProcessados']->count();

        $data['requerimentosDevolvidos'] = RequestForwarding::where('receiver_id', $user->id)
            ->where('status', 'devolvido')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $data['totalDevolvidos'] = $data['requerimentosDevolvidos']->count();

        $tempoMedioQuery = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('request_forwardings.status', ['finalizado', 'indeferido'])
            ->whereBetween('request_forwardings.updated_at', [$startDate, $endDate])
            ->join('requerimentos', 'request_forwardings.requerimento_id', '=', 'requerimentos.id')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at)) as tempo_medio'))
            ->first();

        $data['tempoMedioHoras'] = round($tempoMedioQuery->tempo_medio ?? 0, 1);
        $data['tempoMedioDias']  = round(($tempoMedioQuery->tempo_medio ?? 0) / 24, 1);

        $data['estatisticasPorStatus'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('request_forwardings.created_at', [$startDate, $endDate])
            ->select('request_forwardings.status', DB::raw('count(*) as total'))
            ->groupBy('request_forwardings.status')
            ->orderByDesc('total')
            ->get();

        $data['tiposFrequentes'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('request_forwardings.created_at', [$startDate, $endDate])
            ->join('requerimentos', 'request_forwardings.requerimento_id', '=', 'requerimentos.id')
            ->select('requerimentos.tipoRequisicao', DB::raw('count(*) as total'))
            ->groupBy('requerimentos.tipoRequisicao')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $data['resolucaoEficiente'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('request_forwardings.status', ['finalizado', 'indeferido'])
            ->whereBetween('request_forwardings.updated_at', [$startDate, $endDate])
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at) < 24 THEN "Menos de 1 dia"
                    WHEN TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at) < 48 THEN "1 dia" 
                    WHEN TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at) < 72 THEN "2 dias"
                    WHEN TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at) < 168 THEN "3-7 dias"
                    ELSE "Mais de 7 dias"
                END as faixa_tempo'),
                DB::raw('count(*) as total')
            )
            ->groupBy('faixa_tempo')
            ->orderBy(DB::raw('CASE 
                WHEN faixa_tempo = "Menos de 1 dia" THEN 1
                WHEN faixa_tempo = "1 dia" THEN 2
                WHEN faixa_tempo = "2 dias" THEN 3
                WHEN faixa_tempo = "3-7 dias" THEN 4
                ELSE 5
            END'))
            ->get();

        return $data;
    }
}
