<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
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
    
        // Atualiza apenas os campos permitidos
        $user->fill($request->validated());
    
        // Se o email foi alterado, resetar a verificação
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // Salvar as mudanças no banco
        $user->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB máx.
        ]);
    
        $user = Auth::user();
    
        // Deletar a foto antiga (se existir)
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
    
        // Salvar a nova foto
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
    
        // Atualizar o caminho da foto no banco de dados
        $user->update(['profile_photo_path' => $path]);
    
        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }
}