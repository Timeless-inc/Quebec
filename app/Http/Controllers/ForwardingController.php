<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\RequestForwarding;
use App\Models\User;
use App\Models\Role;
use App\Models\Notification;
use App\Events\ApplicationStatusChanged;
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
        $newForwarding = RequestForwarding::create([
            'requerimento_id' => $requerimento->id,
            'sender_id'       => Auth::user()->id,
            'receiver_id'     => $request->receiver_id,
            'status'          => 'encaminhado',
        ]);

        $oldStatus = $requerimento->status;
        $requerimento->status = 'encaminhado';
        $requerimento->save();

        $alunoUser = User::where('email', $requerimento->email)->first();
        if ($alunoUser && $alunoUser->role === 'Aluno') {
            try {
                
                Notification::create([
                    'user_id' => $alunoUser->id,
                    'title' => 'Requerimento Encaminhado',
                    'message' => "Seu requerimento #" . $requerimento->id . " foi encaminhado para avaliação de um responsável. Você receberá uma notificação quando houver uma atualização.",
                    'event_type' => 'requirement_forwarded',
                    'related_id' => $requerimento->id,
                    'is_read' => false
                ]);
                Log::info('Notificação de encaminhamento criada para aluno', [
                    'user_id' => $alunoUser->id,
                    'request_id' => $requerimento->id,
                    'receiver_id' => $request->receiver_id
                ]);
            } catch (\Exception $notificationError) {
                Log::error('Erro ao criar notificação de encaminhamento para aluno', [
                    'message' => $notificationError->getMessage()
                ]);
            }
        }

        event(new \App\Events\ApplicationStatusChanged($requerimento, $oldStatus, 'encaminhado'));

        // Dispara evento de encaminhamento para notificar o destinatário
        $recipientUser = User::find($request->receiver_id);
        if ($recipientUser) {
            event(new \App\Events\RequirementForwarded($newForwarding, $recipientUser));
        }

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

        $newForwarding = RequestForwarding::create([
            'requerimento_id'  => $requerimento->id,
            'sender_id'        => Auth::user()->id,
            'receiver_id'      => $request->receiver_id,
            'status'           => 'encaminhado',
            'internal_message' => $request->internal_message,
        ]);

        $oldStatus = $requerimento->status;
        $requerimento->status = 'encaminhado';
        $requerimento->save();

        $alunoUser = User::where('email', $requerimento->email)->first();
        if ($alunoUser && $alunoUser->role === 'Aluno') {
            try {
                
                Notification::create([
                    'user_id' => $alunoUser->id,
                    'title' => 'Requerimento Encaminhado',
                    'message' => "Seu requerimento #" . $requerimento->id . " foi encaminhado para avaliação de um responsável. Você receberá uma notificação quando houver uma atualização.",
                    'event_type' => 'requirement_forwarded',
                    'related_id' => $requerimento->id,
                    'is_read' => false
                ]);
                Log::info('Notificação de encaminhamento criada para aluno (reencaminhamento)', [
                    'user_id' => $alunoUser->id,
                    'request_id' => $requerimento->id,
                    'receiver_id' => $request->receiver_id
                ]);
            } catch (\Exception $notificationError) {
                Log::error('Erro ao criar notificação de reencaminhamento para aluno', [
                    'message' => $notificationError->getMessage()
                ]);
            }
        }

        event(new \App\Events\ApplicationStatusChanged($requerimento, $oldStatus, 'encaminhado'));

        // Dispara evento de encaminhamento para notificar o novo destinatário
        $recipientUser = User::find($request->receiver_id);
        if ($recipientUser) {
            event(new \App\Events\RequirementForwarded($newForwarding, $recipientUser));
        }

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

        if (in_array($request->action, ['finalizado', 'indeferido', 'deferido'])) {
            $oldStatus = $requerimento->status;
            $requerimento->status        = $request->action;
            $requerimento->finalizado_por = Auth::user()->name;
            $requerimento->save();

            $aluno = User::where('cpf', $requerimento->cpf)
            ->orWhere('email', $requerimento->email)
            ->first();

        if ($aluno && $aluno->role === 'Aluno') {
            try {
                $statusText = $request->action === 'finalizado' ? 'Aprovado' : 'Indeferido';
                Notification::create([
                    'user_id' => $aluno->id,
                    'title' => $statusText === 'Aprovado' ? 'Requerimento Deferido!' : 'Requerimento Indeferido!',
                    'message' => "Seu requerimento #" . $requerimento->id . " foi " . ($statusText === 'Aprovado' ? 'DEFERIDO' : 'INDEFERIDO') . ".",
                    'event_type' => 'status_' . $request->action,
                    'related_id' => $requerimento->id,
                    'is_read' => false
                ]);
                Log::info('Notificação pop-up criada com sucesso para aluno (Forwarding)', [
                    'user_id' => $aluno->id,
                    'request_id' => $requerimento->id,
                    'status' => $request->action
                ]);
            } catch (\Exception $notificationError) {
                Log::error('Erro ao criar notificação pop-up', [
                    'message' => $notificationError->getMessage()
                ]);
            }
        }
            
            event(new \App\Events\ApplicationStatusChanged($requerimento, $oldStatus, $request->action));
        } else {
            event(new \App\Events\ApplicationStatusChanged($requerimento, $requerimento->status, $requerimento->status));
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

        $oldStatus = $forwarding->requerimento->status;
        $requerimento         = $forwarding->requerimento;
        $requerimento->status = 'devolvido';
        $requerimento->save();

         $aluno = User::where('cpf', $requerimento->cpf)
        ->orWhere('email', $requerimento->email)
        ->first();

    if ($aluno && $aluno->role === 'Aluno') {
        try {
            Notification::create([
                'user_id' => $aluno->id,
                'title' => 'Requerimento Devolvido',
                'message' => 'Seu requerimento #' . $requerimento->id . ' foi devolvido  à CRADT. Você será notificado quando houver uma atualização.',
                'is_read' => false,
                'event_type' => 'request_returned',
                'related_id' => $requerimento->id,
            ]);
            Log::info('Notificação de devolução criada para aluno (Forwarding)', [
                'user_id' => $aluno->id,
                'request_id' => $requerimento->id
            ]);
        } catch (\Exception $notificationError) {
            Log::error('Erro ao criar notificação de devolução', [
                'message' => $notificationError->getMessage()
            ]);
        }
    }

        event(new \App\Events\ApplicationStatusChanged($requerimento, $oldStatus, 'devolvido'));

        return redirect()->back()->with('success', 'Requerimento devolvido para o CRADT com sucesso');
    }
}
