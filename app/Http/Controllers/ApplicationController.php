<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        return view('application.create');
    }

    /**
     * Processa o envio do formulário.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:requerimentos,cpf',
            'celular' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'rg' => 'required|string|max:20',
            'orgaoExpedidor' => 'required|string|max:50',
            'campus' => 'required|string|max:255',
            'matricula' => 'required|string|max:50|unique:requerimentos,matricula',
            'situacao' => 'required|in:1,2,3',
            'curso' => 'required|string|max:255',
            'periodo' => 'required|in:1,2,3,4,5,6',
            'turno' => 'required|in:manhã,tarde',
            'tipoRequisicao' => 'required|integer',
            'anexarArquivos' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        // Verifica se há um arquivo para upload
        if ($request->hasFile('anexarArquivos')) {
            $filePath = $request->file('anexarArquivos')->store('requerimentos_arquivos', 'public');
            $validatedData['anexarArquivos'] = $filePath;
        }

        // Salva os dados no banco
        ApplicationRequest::create($validatedData);

        return redirect()->route('application.create')->with('success', 'Requerimento enviado com sucesso!');
    }

    /**
     * Exibe a lista de requerimentos.
     */
    public function index()
    {
        $requerimentos = ApplicationRequest::latest()->paginate(10);
        return view('application.index', compact('requerimentos'));
    }

    /**
     * Exibe um requerimento específico.
     */
    public function show($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        return view('application.show', compact('requerimento'));
    }

    /**
     * Exclui um requerimento.
     */
    public function destroy($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);

        // Se houver um arquivo anexado, exclui do storage
        if ($requerimento->anexarArquivos) {
            Storage::disk('public')->delete($requerimento->anexarArquivos);
        }

        $requerimento->delete();

        return redirect()->route('application.index')->with('success', 'Requerimento excluído com sucesso!');
    }
}
