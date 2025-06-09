<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\RequestForwarding;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForwardingController extends Controller
{
    public function showForwardForm($requerimentoId)
    {
        $requerimento = ApplicationRequest::findOrFail($requerimentoId);

        $coordenadores = User::whereHas('roles', function ($query) {
            $query->where('name', 'coordenador');
        })->get();

        $professores = User::whereHas('roles', function ($query) {
            $query->where('name', 'professor');
        })->get();

        return view('forwardings.create', compact('requerimento', 'coordenadores', 'professores'));
    }

    public function forward(Request $request, $requerimentoId)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $requerimento = ApplicationRequest::findOrFail($requerimentoId);
        RequestForwarding::create([
            'requerimento_id' => $requerimento->id,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'status' => 'encaminhado',
        ]);

        $requerimento->status = 'encaminhado';
        $requerimento->save();

        return redirect()->back()->with('success', 'Requerimento encaminhado com sucesso.');
    }

    public function viewForwarded()
    {
        $forwardings = RequestForwarding::where('sender_id', Auth::user()->id)
            ->with(['requerimento', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('forwardings.index', compact('forwardings'));
    }
}
