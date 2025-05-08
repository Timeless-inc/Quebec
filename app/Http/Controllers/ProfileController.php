<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ProfileChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
                    'status' => 'pending',
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
                    'status' => 'pending',
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
                    'status' => 'pending',
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
                    'status' => 'pending',
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
}