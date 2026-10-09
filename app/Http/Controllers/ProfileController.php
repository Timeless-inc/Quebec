<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ProfileChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\User;
use App\Rules\MatriculaDisponivel;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        $user = $request->user();

        $profileRequests = ProfileChangeRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'profileRequests' => $profileRequests
        ]);
    }


    public function cradtView(): View
    {
        $user = Auth::user();

        $profileRequests = ProfileChangeRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.cradt', [
            'profileRequests' => $profileRequests
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validatedData = $request->validated();
        $validatedData += $request->validate([
            'cpf' => 'required|string|size:11|unique:users,cpf,' . $user->id,
            'rg' => 'required|string|max:15',
            'matricula' => ['required', 'string', 'max:20', new MatriculaDisponivel($user->id)],
            'matriculas_adicionais' => ['nullable', 'array'],
            'matriculas_adicionais.*' => ['nullable', 'string', 'max:20', 'distinct', 'different:matricula', new MatriculaDisponivel($user->id)],
        ], [
            'matriculas_adicionais.*.distinct' => 'As matrículas adicionais não podem se repetir.',
            'matriculas_adicionais.*.different' => 'A matrícula adicional deve ser diferente da matrícula principal.',
        ]);

        $user->fill(collect($validatedData)->except('matriculas_adicionais')->all());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $user->sincronizarMatriculasAdicionais($validatedData['matriculas_adicionais'] ?? []);

        return Redirect::route('profile.edit')
            ->with('notification', [
                'message' => 'Perfil atualizado com sucesso!',
                'type' => 'success'
            ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function requestUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'fields' => 'required|array',
        ]);

        $fields = $request->input('fields');
        $user = Auth::user();
        
        $fieldLabels = [
            'name' => 'Nome',
            'matricula' => 'Matrícula Principal',
            'cpf' => 'CPF',
            'rg' => 'RG',
        ];
        
        foreach ($fields as $fieldName => $fieldData) {
            if (!isset($fieldData['selected']) || $fieldData['selected'] != 'on') {
                continue;
            }
            
            $label = $fieldLabels[$fieldName] ?? $fieldName;
            
            // Cada matrícula adicional vem do formulário com um sufixo próprio (id ou "nova")
            $isMatriculaAdicional = str_starts_with($fieldName, 'matricula_adicional');

            if ($isMatriculaAdicional) {
                $label = 'Matrícula Adicional';

                if (!empty($fieldData['remove'])) {
                    continue;
                }
            }

            if (!isset($fieldData['value']) || !$fieldData['value']) {
                return back()->withErrors(["O valor para o campo <b>'{$label}'</b> é obrigatório."])->withInput();
            }
            
            if ($fieldName === 'matricula' || $isMatriculaAdicional) {
                if (User::matriculaEmUso($fieldData['value'], $user->id) || in_array($fieldData['value'], $user->todasMatriculas())) {
                    return back()
                        ->withInput()
                        ->withErrors(["A matrícula '{$fieldData['value']}' já está cadastrada, para você ou para outro usuário."]);
                }
            }

            if (in_array($fieldName, ['cpf', 'rg'])) {
                $existingUser = User::where($fieldName, $fieldData['value'])
                    ->where('id', '!=', $user->id)
                    ->first();
                    
                if ($existingUser) {
                    return back()
                        ->withInput()
                        ->withErrors(["O(a) <b>'{$label}'</b> '{$fieldData['value']}' já está registrado(a) para outro usuário."]);
                }
            }
        }
        
        $requestGroupId = Str::uuid()->toString();
        $hasSelectedField = false;

        foreach ($fields as $fieldName => $fieldData) {
            if (!isset($fieldData['selected']) || $fieldData['selected'] != 'on') {
                continue;
            }

            $hasSelectedField = true;
            $isMatriculaAdicional = str_starts_with($fieldName, 'matricula_adicional');
            $label = $isMatriculaAdicional ? 'Matrícula Adicional' : ($fieldLabels[$fieldName] ?? $fieldName);

            if (!$request->hasFile("fields.{$fieldName}.document")) {
                return back()->withErrors(["O documento comprobatório para o campo <b>'{$label}'</b> é obrigatório."])->withInput();
            }

            try {
                $document = $request->file("fields.{$fieldName}.document");
                $path = $document->store('profile-change-docs', 'public');
                
                $changeRequest = ProfileChangeRequest::create([
                    'user_id' => $user->id,
                    'field' => $isMatriculaAdicional ? 'matricula_adicional' : $fieldName,
                    'current_value' => $fieldData['current'] ?? null,
                    'new_value' => $isMatriculaAdicional && !empty($fieldData['remove']) ? '' : $fieldData['value'],
                    'document_path' => $path,
                    'status' => 'pending',
                    'request_group_id' => $requestGroupId,
                ]);
                
                
                
            } catch (\Exception $e) {
                
                return back()->withErrors(["Erro ao processar a solicitação para <b>'{$label}'</b>: " . $e->getMessage()]);
            }
        }

        if (!$hasSelectedField) {
            return back()->withErrors(['Você precisa selecionar pelo menos um campo para alteração.']);
        }

        return back()->with('status', 'Solicitação de alteração enviada com sucesso!');
    }
}
