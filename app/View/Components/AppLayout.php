<?php

namespace App\View\Components;

use App\Models\Role;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $user = auth()->user();

        if ($user?->role === 'Cradt') {
            return view('layouts.appcradt');
        }

        if ($user?->role === 'Aluno') {
            return view('layouts.app');
        }

        if ($user && ($user->isDiretorGeral() || Role::where('label', $user->role)->exists())) {
            return view('layouts.app-diretor-geral');
        }

        return view('layouts.app');
    }
}
