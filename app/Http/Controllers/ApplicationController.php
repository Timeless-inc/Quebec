<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Guid;

class ApplicationController extends Controller
{
    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        $user = Auth::user();
        return view('application.create', [
            'nomeCompleto' => $user->name,
            'matricula' => $user->matricula,
            'email' => $user->email,
            'cpf' => $user->cpf,
            'data' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Processa o envio do formulário.
     * 'cpf' => 'required|string|unique:requerimentos,cpf|max:14', 
     * 'matricula' => 'required|string|max:50|unique:requerimentos,matricula',
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'cpf' => 'required|string|max:14',  // removed unique validation
            'celular' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'rg' => 'required|string|max:20',
            'orgaoExpedidor' => 'required|string|max:50',
            'campus' => 'required|string|max:255',
            'matricula' => 'required|string|max:50', // removed unique validation
            'situacao' => 'required|in:1,2,3',
            'curso' => 'required|string|max:255',
            'periodo' => 'required|in:1,2,3,4,5,6',
            'turno' => 'required|in:manhã,tarde',
            'tipoRequisicao' => 'required|integer',
            'anexarArquivos' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        // Mapeamento dos valores para os nomes do tipoRequisicao
        $tiposRequisicao = [
            1 => 'Admissão por Transferência e Análise Curricular',
            2 => 'Ajuste de Matrícula Semestral',
            3 => 'Autorização para cursar disciplinas em outras Instituições de Ensino Superior',
            4 => 'Cancelamento de Matrícula',
            5 => 'Cancelamento de Disciplina',
            6 => 'Certificado de Conclusão',
            7 => 'Certidão - Autenticidade',
            8 => 'Complementação de Matrícula',
            9 => 'Cópia Xerox de Documento',
            10 => 'Declaração de Colação de Grau e Tramitação de Diploma',
            11 => 'Declaração de Matrícula ou Matrícula Vínculo',
            12 => 'Declaração de Monitoria',
            13 => 'Declaração para Estágio',
            14 => 'Diploma 1ªvia/2ªvia',
            15 => 'Dispensa da prática de Educação Física',
            16 => 'Declaração Tramitação de Diploma',
            17 => 'Ementa de disciplina',
            18 => 'Guia de Transferência',
            19 => 'Histórico Escolar',
            20 => 'Isenção de disciplinas cursadas',
            21 => 'Justificativa de falta(s) ou prova 2º chamada',
            22 => 'Matriz curricular',
            23 => 'Reabertura de Matrícula',
            24 => 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC',
            25 => 'Reintegração para Cursar',
            26 => 'Solicitação de Conselho de Classe',
            27 => 'Trancamento de Matrícula',
            28 => 'Transferência de Turno',
            29 => 'Outros',
        ];

        $situacoes = [
            1 => 'Ativo',
            2 => 'Inativo',
            3 => 'Desvinculado',
        ];

        // Obtém o nome da requisição com base no valor
        $tipoRequisicaoNome = $tiposRequisicao[$validatedData['tipoRequisicao']];
        $situacaoNome = $situacoes[$validatedData['situacao']];

        // Substitui o valor do tipoRequisicao pelo nome
        $validatedData['tipoRequisicao'] = $tipoRequisicaoNome;
        $validatedData['situacao'] = $situacaoNome;

        // Verifica se há um arquivo para upload
        if ($request->hasFile('anexarArquivos')) {
            $filePath = $request->file('anexarArquivos')->store('requerimentos_arquivos', 'public');
            $validatedData['anexarArquivos'] = $filePath;
        }

        // Gerando o valor para o campo 'key' (UUID)
        $validatedData['key'] = Guid::uuid4()->toString(); // Gerando um UUID para o campo 'key'

        // Salva os dados no banco
        ApplicationRequest::create($validatedData);

        return redirect()->route('application.create')->with('success', 'Requerimento enviado com sucesso!');
    }


    /**
     * Exibe a lista de requerimentos.
     */
    public function index()
    {
        $requerimentos = ApplicationRequest::where('email', Auth::user()->email)
            ->latest()
            ->paginate(10);

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

    public function updateStatus(Request $request, $id)
    {
        $application = ApplicationRequest::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
    }
}
