<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user(); // Obtém o usuário autenticado

        // Valida os campos adicionais (CPF, RG e Matrícula)
        $validatedData = $request->validated();
        $validatedData += $request->validate([
            'cpf' => 'required|string|size:11|unique:users,cpf,' . $user->id,
            'rg' => 'required|string|max:15',
            'matricula' => 'required|string|max:20|unique:users,matricula,' . $user->id,
        ]);

        // Atualiza apenas os campos permitidos
        $user->fill($validatedData);

        // Se o email foi alterado, resetar a verificação
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Salvar as mudanças no banco
        $user->save();

        return Redirect::route('profile.edit')
            ->with('notification', [
                'message' => 'Perfil atualizado com sucesso!',
                'type' => 'success'
            ]);
    }

    /**
     * Delete the user's account.
     */
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
}