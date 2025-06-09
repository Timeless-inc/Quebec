<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForwarding;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        $forwardings = RequestForwarding::where('receiver_id', $userId)
            ->where('status', 'encaminhado')
            ->with('requerimento')
            ->latest()
            ->get();
        
        return view('coordinator.dashboard', compact('forwardings'));
    }
    
    public function processRequest(Request $request, $forwardingId)
    {
        $forwarding = RequestForwarding::findOrFail($forwardingId);
        $action = $request->input('action');
        
        if (!in_array($action, ['deferido', 'indeferido', 'pendente'])) {
            return redirect()->back()->with('error', 'Ação inválida');
        }
        
        $forwarding->status = $action;
        $forwarding->save();
        
        $requerimento = $forwarding->requerimento;
        $requerimento->status = $action;
        $requerimento->save();
        
        return redirect()->back()->with('success', 'Requerimento processado com sucesso');
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