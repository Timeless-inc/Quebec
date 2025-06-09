<?php

namespace App\Http\Controllers;

use App\Models\RequestForwarding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        if ($forwarding->receiver_id != Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para processar este requerimento.');
        }
        
        $forwarding->status = $request->action;
        $forwarding->save();
        
        if (in_array($request->action, ['deferido', 'indeferido'])) {
            $requerimento = $forwarding->requerimento;
            $requerimento->status = $request->action;
            $requerimento->save();
        }
        
        return redirect()->back()->with('success', 'Requerimento processado com sucesso.');
    }
    
    public function returnRequest(Request $request, $forwardingId)
    {
        $forwarding = RequestForwarding::findOrFail($forwardingId);
        
        if ($forwarding->receiver_id != Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para devolver este requerimento.');
        }
        
        $forwarding->status = 'devolvido';
        $forwarding->internal_message = $request->internal_message;
        $forwarding->is_returned = true;
        $forwarding->save();
        
        return redirect()->back()->with('success', 'Requerimento devolvido com sucesso.');
    }
}