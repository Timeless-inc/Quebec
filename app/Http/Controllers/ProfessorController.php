<?php

namespace App\Http\Controllers;

use App\Models\RequestForwarding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfessorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $forwardings = RequestForwarding::where('receiver_id', $user->id)
            ->with('requerimento')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('professor.dashboard', compact('forwardings'));
    }

    public function processRequest(Request $request, $forwardingId)
    {
        $forwarding = RequestForwarding::findOrFail($forwardingId);
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
                $path = $file->store('requerimentos_arquivos', 'public');
                $anexos[] = $path;
            }

            $anexosAntigos = $requerimento->anexos_finalizacao ? json_decode($requerimento->anexos_finalizacao, true) : [];
            $todosAnexos = array_merge($anexosAntigos, $anexos);

            $requerimento->anexos_finalizacao = json_encode($todosAnexos);
        }

        $forwarding->save();

        if (in_array($request->action, ['finalizado', 'indeferido'])) {
            $forwarding->status = $request->action;
            $forwarding->save();
            
            $requerimento->status = $request->action;
            $requerimento->finalizado_por = Auth::user();
            $requerimento->finalizado_em = now();
            $requerimento->save();
        }

        if ($request->action === 'pendente') {
            $requerimento->status = 'pendente';
        }

        $requerimento->save();

        return redirect()->back()->with('success', 'Requerimento processado com sucesso.');
    }

    public function returnRequest(Request $request, $forwardingId)
    {
        try {
            $forwarding = RequestForwarding::findOrFail($forwardingId);

            // Verificar se o professor tem permissão
            if ($forwarding->receiver_id != Auth::id()) {
                return redirect()->back()->with('error', 'Você não tem permissão para devolver este requerimento.');
            }

            // Atualizar o status do encaminhamento
            $forwarding->status = 'devolvido';
            if ($request->has('internal_message')) {
                $forwarding->internal_message = $request->internal_message;
            }
            $forwarding->is_returned = true;
            $forwarding->save();

            return redirect()->back()->with('success', 'Requerimento devolvido com sucesso.');
        } catch (\Exception $e) {
            // Log do erro
            \Illuminate\Support\Facades\Log::error('Erro ao devolver requerimento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao devolver o requerimento: ' . $e->getMessage());
        }
    }
}
