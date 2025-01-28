<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;

class JustificativaAlunoController extends Controller
{
    public function index()
{
    $requerimentos = ApplicationRequest::all(); // Ou qualquer método que traga os dados de requerimentos
    return view('application', compact('requerimentos'));
}
    
    public function show($id)
    {
        // Buscar o requerimento pelo ID
        $requerimento = ApplicationRequest::findOrFail($id);
    
        // Passar os dados necessários para a view e para o componente
        return view('application.show', [
            'id' => $requerimento->id,
            'nome' => $requerimento->nomeCompleto,
            'matricula' => $requerimento->matricula,
            'email' => $requerimento->email,
            'cpf' => $requerimento->cpf,
            'datas' => $requerimento->created_at->toDateString(),
            'andamento' => $requerimento->situacao,
            'anexos' => json_decode($requerimento->anexarArquivos) ?? [],
            'observacoes' => $requerimento->observacoes
        ]);
    }
}
