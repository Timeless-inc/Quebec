<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\RequestForwarding;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $forwardings = RequestForwarding::with(['requerimento', 'sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('forwardings.index', compact('forwardings'));
    }
    public function showForwardFormForCoordinatorProfessor($forwardingId)
    {
        try {
            $forwarding = RequestForwarding::with(['requerimento'])->findOrFail($forwardingId);
            $requerimento = $forwarding->requerimento;

            if ($forwarding->receiver_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Você não tem permissão para encaminhar este requerimento.');
            }

            $coordenadores = User::where('role', 'Coordenador')
                ->where('id', '!=', Auth::id())
                ->get();

            $professores = User::where('role', 'Professor')
                ->where('id', '!=', Auth::id())
                ->get();

            return view('forwardings.create-secondary', compact('forwarding', 'requerimento', 'coordenadores', 'professores'));
        } catch (\Exception $e) {
            Log::error('Erro no showForwardFormForCoordinatorProfessor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar o formulário de encaminhamento: ' . $e->getMessage());
        }
    }

    public function forwardFromCoordinatorProfessor(Request $request, $forwardingId)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'internal_message' => 'nullable|string|max:1000'
        ]);

        $originalForwarding = RequestForwarding::findOrFail($forwardingId);
        $requerimento = $originalForwarding->requerimento;

        if ($originalForwarding->receiver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para encaminhar este requerimento.');
        }

        $originalForwarding->status = 'reencaminhado';
        $originalForwarding->save();

        RequestForwarding::create([
            'requerimento_id' => $requerimento->id,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'status' => 'encaminhado',
            'internal_message' => $request->internal_message,
        ]);

        $requerimento->status = 'encaminhado';
        $requerimento->save();

        $user = Auth::user();
        if ($user->role === 'Coordenador') {
            return redirect()->route('coordinator.dashboard')->with('success', 'Requerimento reencaminhado com sucesso.');
        } elseif ($user->role === 'Professor') {
            return redirect()->route('professor.dashboard')->with('success', 'Requerimento reencaminhado com sucesso.');
        }

        return redirect()->back()->with('success', 'Requerimento reencaminhado com sucesso.');
    }
}
