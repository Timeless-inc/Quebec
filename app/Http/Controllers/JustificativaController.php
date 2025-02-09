<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Http\Request;

class JustificativaController extends Controller
{
    /**
     * Exibe todos os requerimentos.
     */
    public function index()
{
    $requerimentos = ApplicationRequest::all();
    dd($requerimentos); // Isso vai parar a execução e mostrar os dados
    return view('application', compact('requerimentos'));
}

    /**
     * Exibe os detalhes de um requerimento específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Buscar o requerimento com o ID fornecido
        $requerimento = ApplicationRequest::findOrFail($id);

        // Retorna a view com os dados do requerimento
        return view('justificativas.show', compact('requerimento'));
    }
}
