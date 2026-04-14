<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private function requireDiretorGeral()
    {
        if (!auth()->user()->isDiretorGeral()) {
            abort(403, 'Apenas o Diretor Geral pode gerenciar cargos.');
        }
    }

    public function index()
    {
        $this->requireDiretorGeral();
        $roles = Role::orderBy('label')->get();
        return view('cargos.index', compact('roles'));
    }

    public function create()
    {
        $this->requireDiretorGeral();
        return view('cargos.create');
    }

    public function store(Request $request)
    {
        $this->requireDiretorGeral();
        $validated = $request->validate([
            'label'                  => 'required|string|max:100',
            'can_receive_forwardings' => 'nullable|boolean',
        ], [
            'label.required' => 'O nome do cargo é obrigatório.',
            'label.max'      => 'O nome do cargo deve ter no máximo 100 caracteres.',
        ]);

        $name = \Illuminate\Support\Str::slug($validated['label'], '_');

        $exists = Role::whereRaw('LOWER(label) = ?', [strtolower($validated['label'])])->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['label' => 'Já existe um cargo com este nome.']);
        }

        $fixedRoles = ['aluno', 'cradt', 'manager', 'diretor geral'];
        if (in_array(strtolower($validated['label']), $fixedRoles)) {
            return back()->withInput()->withErrors(['label' => 'Este nome é reservado para cargos do sistema.']);
        }

        Role::create([
            'name'                   => $name,
            'label'                  => $validated['label'],
            'can_receive_forwardings' => $request->boolean('can_receive_forwardings'),
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo criado com sucesso!');
    }

    public function edit(Role $cargo)
    {
        $this->requireDiretorGeral();
        return view('cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Role $cargo)
    {
        $this->requireDiretorGeral();
        $validated = $request->validate([
            'label'                  => 'required|string|max:100',
            'can_receive_forwardings' => 'nullable|boolean',
        ], [
            'label.required' => 'O nome do cargo é obrigatório.',
        ]);

        $exists = Role::whereRaw('LOWER(label) = ?', [strtolower($validated['label'])])
            ->where('id', '!=', $cargo->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['label' => 'Já existe um cargo com este nome.']);
        }

        $fixedRoles = ['aluno', 'cradt', 'manager', 'diretor geral'];
        if (in_array(strtolower($validated['label']), $fixedRoles)) {
            return back()->withInput()->withErrors(['label' => 'Este nome é reservado para cargos do sistema.']);
        }

        $oldLabel = $cargo->label;
        $newLabel = $validated['label'];
        $newName  = \Illuminate\Support\Str::slug($newLabel, '_');

        $cargo->update([
            'name'                   => $newName,
            'label'                  => $newLabel,
            'can_receive_forwardings' => $request->boolean('can_receive_forwardings'),
        ]);

        if ($oldLabel !== $newLabel) {
            User::where('role', $oldLabel)->update(['role' => $newLabel]);
        }

        return redirect()->route('cargos.index')->with('success', 'Cargo atualizado com sucesso!');
    }

    public function destroy(Role $cargo)
    {
        $this->requireDiretorGeral();
        $count = User::where('role', $cargo->label)->count();

        if ($count > 0) {
            return back()->with('error', "Não é possível excluir o cargo \"{$cargo->label}\" pois há {$count} usuário(s) vinculado(s) a ele.");
        }

        $cargo->delete();

        return redirect()->route('cargos.index')->with('success', 'Cargo excluído com sucesso!');
    }
}
