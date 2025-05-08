<?php

namespace App\Http\Controllers;

use App\Models\ProfileChangeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileChangeRequestController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();
        $fieldChanges = [];

        if ($request->filled('name') && $request->name !== $user->name) {
            if ($request->hasFile('name_document')) {
                $path = $request->file('name_document')->store('profile-documents');
                $fieldChanges[] = [
                    'user_id' => $user->id,
                    'field' => 'name',
                    'current_value' => $user->name,
                    'new_value' => $request->name,
                    'document_path' => $path,
                    'status' => ProfileChangeRequest::STATUS_PENDING,
                ];
            }
        }

        if ($request->filled('matricula') && $request->matricula !== $user->matricula) {
            if ($request->hasFile('matricula_document')) {
                $path = $request->file('matricula_document')->store('profile-documents');
                $fieldChanges[] = [
                    'user_id' => $user->id,
                    'field' => 'matricula',
                    'current_value' => $user->matricula,
                    'new_value' => $request->matricula,
                    'document_path' => $path,
                    'status' => ProfileChangeRequest::STATUS_PENDING,
                ];
            }
        }

        if ($request->filled('cpf') && $request->cpf !== $user->cpf) {
            if ($request->hasFile('cpf_document')) {
                $path = $request->file('cpf_document')->store('profile-documents');
                $fieldChanges[] = [
                    'user_id' => $user->id,
                    'field' => 'cpf',
                    'current_value' => $user->cpf,
                    'new_value' => $request->cpf,
                    'document_path' => $path,
                    'status' => ProfileChangeRequest::STATUS_PENDING,
                ];
            }
        }

        if ($request->filled('rg') && $request->rg !== $user->rg) {
            if ($request->hasFile('rg_document')) {
                $path = $request->file('rg_document')->store('profile-documents');
                $fieldChanges[] = [
                    'user_id' => $user->id,
                    'field' => 'rg',
                    'current_value' => $user->rg,
                    'new_value' => $request->rg,
                    'document_path' => $path,
                    'status' => ProfileChangeRequest::STATUS_PENDING,
                ];
            }
        }

        foreach ($fieldChanges as $change) {
            ProfileChangeRequest::create($change);
        }

        if (count($fieldChanges) > 0) {
            return redirect()->route('dashboard')->with('success', 'Solicitação de alteração enviada com sucesso! Aguarde a análise pela CRADT.');
        } else {
            return redirect()->back()->with('error', 'Nenhuma alteração solicitada ou documentos comprobatórios ausentes.');
        }
    }


    public function approve(Request $request, ProfileChangeRequest $profileRequest)
    {
        $user = Auth::user();
        if ($user->role !== 'Cradt' && $user->role !== 'Manager') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $targetUser = User::find($profileRequest->user_id);
        if (!$targetUser) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
        
        $field = $profileRequest->field;
        $targetUser->$field = $profileRequest->new_value;
        $targetUser->save();
        
        $profileRequest->status = 'approved';
        $profileRequest->admin_comment = $request->comment ?? 'Aprovado';
        $profileRequest->save();
        
        return redirect()->back()->with('success', 'Solicitação aprovada com sucesso!');
    }
    
    public function reject(Request $request, ProfileChangeRequest $profileRequest)
    {
        $user = Auth::user();
        if ($user->role !== 'Cradt' && $user->role !== 'Manager') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $profileRequest->status = 'rejected';
        $profileRequest->admin_comment = $request->comment ?? 'Reprovado';
        $profileRequest->save();
        
        return redirect()->back()->with('success', 'Solicitação rejeitada com sucesso!');
    }
    
    public function pendency(Request $request, ProfileChangeRequest $profileRequest)
    {
        $user = Auth::user();
        if ($user->role !== 'Cradt' && $user->role !== 'Manager') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $profileRequest->status = 'needs_review';
        $profileRequest->admin_comment = $request->comment ?? 'Documentação insuficiente';
        $profileRequest->save();
        
        return redirect()->back()->with('success', 'Solicitação marcada para revisão!');
    }

    public function comment(Request $request, ProfileChangeRequest $profileRequest)
    {
        $user = Auth::user();
        if ($user->role !== 'Cradt' && $user->role !== 'Manager') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        
        $profileRequest->admin_comment = $request->comment;
        $profileRequest->save();
        
        return redirect()->back()->with('success', 'Comentário adicionado com sucesso!');
    }
}