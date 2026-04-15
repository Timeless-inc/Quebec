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

    private function getAvailableReceivers(?int $excludeUserId = null): array
    {
        $receivers = [];

        $forwardableLabels = Role::getAllForwardableRoleLabels(); 

        foreach ($forwardableLabels as $label) {
            $query = User::where('role', $label);

            if ($excludeUserId) {
                $query->where('id', '!=', $excludeUserId);
            }

            $users = $query->get();

            if ($users->isNotEmpty()) {
                $receivers[$label] = $users;
            }
        }

        return $receivers;
    }

    public function showForwardForm($requerimentoId)
    {
        $requerimento = ApplicationRequest::findOrFail($requerimentoId);
        $receiversByRole = $this->getAvailableReceivers();

        return view('forwardings.create', compact('requerimento', 'receiversByRole'));
    }

    public function forward(Request $request, $requerimentoId)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $requerimento = ApplicationRequest::findOrFail($requerimentoId);
        RequestForwarding::create([
            'requerimento_id' => $requerimento->id,
            'sender_id'       => Auth::user()->id,
            'receiver_id'     => $request->receiver_id,
            'status'          => 'encaminhado',
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
            $forwarding   = RequestForwarding::with(['requerimento'])->findOrFail($forwardingId);
            $requerimento = $forwarding->requerimento;

            if ($forwarding->receiver_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Você não tem permissão para encaminhar este requerimento.');
            }

            $receiversByRole = $this->getAvailableReceivers(Auth::id());

            return view('forwardings.create-secondary', compact('forwarding', 'requerimento', 'receiversByRole'));
        } catch (\Exception $e) {
            Log::error('Erro no showForwardFormForCoordinatorProfessor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar o formulário de encaminhamento: ' . $e->getMessage());
        }
    }

    public function forwardFromCoordinatorProfessor(Request $request, $forwardingId)
    {
        $request->validate([
            'receiver_id'      => 'required|exists:users,id',
            'internal_message' => 'nullable|string|max:1000',
        ]);

        $originalForwarding = RequestForwarding::findOrFail($forwardingId);
        $requerimento       = $originalForwarding->requerimento;

        if ($originalForwarding->receiver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para encaminhar este requerimento.');
        }

        $originalForwarding->status = 'reencaminhado';
        $originalForwarding->save();

        RequestForwarding::create([
            'requerimento_id'  => $requerimento->id,
            'sender_id'        => Auth::user()->id,
            'receiver_id'      => $request->receiver_id,
            'status'           => 'encaminhado',
            'internal_message' => $request->internal_message,
        ]);

        $requerimento->status = 'encaminhado';
        $requerimento->save();

        $user = Auth::user();
        if ($user->isDiretorGeral()) {
            return redirect()->route('diretor-geral.dashboard')->with('success', 'Requerimento reencaminhado com sucesso.');
        }

        return redirect()->back()->with('success', 'Requerimento reencaminhado com sucesso.');
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
            $todosAnexos              = array_merge($anexosAntigos, $anexos);
            $requerimento->anexos_finalizacao = json_encode($todosAnexos);
        }

        $forwarding->save();

        if (in_array($request->action, ['finalizado', 'indeferido'])) {
            $requerimento->status        = $request->action;
            $requerimento->finalizado_por = Auth::user()->name;
            $requerimento->save();
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

        return redirect()->back()->with('success', 'Requerimento devolvido para o CRADT com sucesso');
    }
}
