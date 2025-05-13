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
            'matricula' => 'required|string|max:20|unique:users,matricula,' . $user->id,
        ]);

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

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
        

        $requestGroupId = Str::uuid()->toString(); 
        $hasSelectedField = false;

        foreach ($fields as $fieldName => $fieldData) {
            if (!isset($fieldData['selected']) || $fieldData['selected'] != 'on') {
                continue;
            }

            $hasSelectedField = true;

            if (!isset($fieldData['value']) || !$fieldData['value']) {
                return back()->withErrors(["O valor para o campo '{$fieldName}' é obrigatório."]);
            }

            if (!$request->hasFile("fields.{$fieldName}.document")) {
                return back()->withErrors(["O documento comprobatório para o campo '{$fieldName}' é obrigatório."]);
            }

            try {
                $document = $request->file("fields.{$fieldName}.document");
                $path = $document->store('profile-change-docs', 'public');
                
                $changeRequest = ProfileChangeRequest::create([
                    'user_id' => $user->id,
                    'field' => $fieldName,
                    'current_value' => $fieldData['current'],
                    'new_value' => $fieldData['value'],
                    'document_path' => $path,
                    'status' => 'pending',
                    'request_group_id' => $requestGroupId,
                ]);
                
                
                
            } catch (\Exception $e) {
                
                return back()->withErrors(["Erro ao processar a solicitação para '{$fieldName}': " . $e->getMessage()]);
            }
        }

        if (!$hasSelectedField) {
            return back()->withErrors(['Você precisa selecionar pelo menos um campo para alteração.']);
        }

        return back()->with('status', 'Solicitação de alteração enviada com sucesso!');
    }
}
