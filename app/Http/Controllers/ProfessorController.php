<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForwarding;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        $forwardings = RequestForwarding::where('receiver_id', $userId)
            ->where('status', 'encaminhado')
            ->with('requerimento')
            ->latest()
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
            $requerimento->status = $request->action;
            $requerimento->finalizado_por = Auth::user()->name;
            $requerimento->save();
        }
        
        return redirect()->back()->with('success', 'Requerimento processado com sucesso.');
    }
    
    public function returnRequest(Request $request, $forwardingId)
    {
        $forwarding = RequestForwarding::findOrFail($forwardingId);
        
        $forwarding->status = 'devolvido';
        $forwarding->internal_message = $request->input('internal_message');
        $forwarding->is_returned = true;
        $forwarding->save();
        
        $requerimento = $forwarding->requerimento;
        $requerimento->status = 'devolvido';
        $requerimento->save();
        
        return redirect()->back()->with('success', 'Requerimento devolvido com sucesso');
    }
}