<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\RequestForwarding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CoordinatorReportController extends Controller
{
    public function index()
    {
        return view('coordinator.reports');
    }

    public function generateProcessedReport(Request $request)
    {
        $user = Auth::user();
        $period = $request->input('period', 'mes-atual');
        
        switch ($period) {
            case 'mes-atual':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodoTexto = 'Mês Atual: ' . $startDate->format('m/Y');
                break;
                
            case 'mes-anterior':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $periodoTexto = 'Mês Anterior: ' . $startDate->format('m/Y');
                break;
                
            case 'ano-atual':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $periodoTexto = 'Ano Atual: ' . $startDate->format('Y');
                break;
                
            case 'personalizado':
                $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
                $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
                $periodoTexto = 'Período: ' . $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');
                break;
                
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodoTexto = 'Mês Atual: ' . $startDate->format('m/Y');
        }

        $data = $this->getCoordinatorData($user, $startDate, $endDate);

        $pdf = PDF::loadView('coordinator.reports.processed-pdf', [
            'user' => $user,
            'data' => $data,
            'periodoTexto' => $periodoTexto,
            'dataGeracao' => now()->format('d/m/Y H:i')
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-coordenador-processados-' . now()->format('dmY-His') . '.pdf');
    }

    public function generatePeriodReport(Request $request)
    {
        $user = Auth::user();
        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
        $periodoTexto = 'Período: ' . $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');

        $data = $this->getCoordinatorData($user, $startDate, $endDate);

        $pdf = PDF::loadView('coordinator.reports.period-pdf', [
            'user' => $user,
            'data' => $data,
            'periodoTexto' => $periodoTexto,
            'dataGeracao' => now()->format('d/m/Y H:i')
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-coordenador-periodo-' . now()->format('dmY-His') . '.pdf');
    }

    public function getStatistics()
    {
        $user = Auth::user();
        
        // Estatísticas gerais
        $totalProcessados = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('status', ['finalizado', 'indeferido'])
            ->count();

        $totalEncaminhados = RequestForwarding::where('receiver_id', $user->id)
            ->count();

        $totalDevolvidos = RequestForwarding::where('receiver_id', $user->id)
            ->where('status', 'devolvido')
            ->count();

        // Estatísticas por mês nos últimos 6 meses
        $estatisticasMensais = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $startOfMonth = $mes->copy()->startOfMonth();
            $endOfMonth = $mes->copy()->endOfMonth();

            $processados = RequestForwarding::where('receiver_id', $user->id)
                ->whereIn('status', ['finalizado', 'indeferido'])
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();

            $estatisticasMensais[] = [
                'mes' => $mes->format('M/Y'),
                'total' => $processados
            ];
        }

        // Estatísticas por status
        $statusStats = RequestForwarding::where('receiver_id', $user->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'totalProcessados' => $totalProcessados,
            'totalEncaminhados' => $totalEncaminhados,
            'totalDevolvidos' => $totalDevolvidos,
            'estatisticasMensais' => $estatisticasMensais,
            'statusStats' => $statusStats
        ]);
    }

    private function getCoordinatorData($user, $startDate, $endDate)
    {
        $data = [];

        // Requerimentos recebidos (encaminhados para o coordenador)
        $data['requerimentosRecebidos'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data['totalRecebidos'] = $data['requerimentosRecebidos']->count();

        // Requerimentos processados (finalizados ou indeferidos)
        $data['requerimentosProcessados'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('status', ['finalizado', 'indeferido'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $data['totalProcessados'] = $data['requerimentosProcessados']->count();

        // Requerimentos devolvidos
        $data['requerimentosDevolvidos'] = RequestForwarding::where('receiver_id', $user->id)
            ->where('status', 'devolvido')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->with(['requerimento', 'sender'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $data['totalDevolvidos'] = $data['requerimentosDevolvidos']->count();

        // Tempo médio de processamento
        $tempoMedioQuery = RequestForwarding::where('receiver_id', $user->id)
            ->whereIn('request_forwardings.status', ['finalizado', 'indeferido'])
            ->whereBetween('request_forwardings.updated_at', [$startDate, $endDate])
            ->join('requerimentos', 'request_forwardings.requerimento_id', '=', 'requerimentos.id')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, request_forwardings.created_at, request_forwardings.updated_at)) as tempo_medio'))
            ->first();

        $data['tempoMedioHoras'] = round($tempoMedioQuery->tempo_medio ?? 0, 1);
        $data['tempoMedioDias'] = round(($tempoMedioQuery->tempo_medio ?? 0) / 24, 1);

        // Estatísticas por status
        $data['estatisticasPorStatus'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('request_forwardings.created_at', [$startDate, $endDate])
            ->select('request_forwardings.status', DB::raw('count(*) as total'))
            ->groupBy('request_forwardings.status')
            ->orderByDesc('total')
            ->get();

        // Tipos de requerimentos mais frequentes
        $data['tiposFrequentes'] = RequestForwarding::where('receiver_id', $user->id)
            ->whereBetween('request_forwardings.created_at', [$startDate, $endDate])
            ->join('requerimentos', 'request_forwardings.requerimento_id', '=', 'requerimentos.id')
            ->select('requerimentos.tipoRequisicao', DB::raw('count(*) as total'))
            ->groupBy('requerimentos.tipoRequisicao')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Eficiência na resolução
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
