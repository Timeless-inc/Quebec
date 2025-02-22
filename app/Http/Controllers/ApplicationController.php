<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Guid;

class ApplicationController extends Controller
{
    private $tiposRequisicao = [
        1  => 'Admissão por Transferência e Análise Curricular',
        2  => 'Ajuste de Matrícula Semestral',
        3  => 'Autorização para cursar disciplinas em outras Instituições de Ensino Superior',
        4  => 'Cancelamento de Matrícula',
        5  => 'Cancelamento de Disciplina',
        6  => 'Certificado de Conclusão',
        7  => 'Certidão - Autenticidade',
        8  => 'Complementação de Matrícula',
        9  => 'Cópia Xerox de Documento',
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

    private $situacoes = [
        1 => 'Matriculado',
        2 => 'Graduado',
        3 => 'Desvinculado',
    ];

    public function index()
    {
        $requerimentos = ApplicationRequest::where('email', Auth::user()->email)
            ->latest()
            ->paginate(10);

        foreach ($requerimentos as $requerimento) {
            if (is_object($requerimento->tipoRequisicao)) {
                $requerimento->tipoRequisicao = property_exists($requerimento->tipoRequisicao, 'nome')
                    ? $requerimento->tipoRequisicao->nome
                    : (string) $requerimento->tipoRequisicao;
            }
        }

        return view('application.index', compact('requerimentos'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('application.create', [
            'nomeCompleto' => $user->name,
            'matricula'    => $user->matricula,
            'email'        => $user->email,
            'cpf'          => $user->cpf,
            'data'         => now()->format('Y-m-d'),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomeCompleto'     => 'required|string|max:255',
            'cpf'              => 'required|string|max:14',
            'celular'          => 'required|string|max:15',
            'email'            => 'required|email|max:255',
            'rg'               => 'required|string|max:20',
            'orgaoExpedidor'   => 'required|string|max:50',
            'campus'           => 'required|string|max:255',
            'matricula'        => 'required|string|max:50',
            'situacao'         => 'required|in:1,2,3',
            'curso'            => 'required|string|max:255',
            'periodo'          => 'required|in:1,2,3,4,5,6',
            'turno'            => 'required|in:manhã,tarde',
            'tipoRequisicao'   => 'required|integer',
            'anexarArquivos'   => 'nullable',
            'anexarArquivos.*' => 'file|mimes:pdf,jpg,png|max:2048',
            'observacoes'      => 'nullable|string|max:1000',
        ]);

        $validatedData['tipoRequisicao'] = $this->tiposRequisicao[$validatedData['tipoRequisicao']];
        $validatedData['situacao'] = $this->situacoes[$validatedData['situacao']];
        $validatedData['key'] = Guid::uuid4()->toString();

        $attachmentPaths = [];
        if ($request->hasFile('anexarArquivos')) {
            foreach ($request->file('anexarArquivos') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = 'Doc' . ($key + 1) . '.' . $extension;
                $path = $file->storeAs('requerimentos_arquivos', $fileName, 'public');
                $attachmentPaths[] = $path;
            }
        }
        $validatedData['anexarArquivos'] = !empty($attachmentPaths) ? json_encode($attachmentPaths) : null;

        ApplicationRequest::create($validatedData);

        return redirect()->route('dashboard')
            ->with('success', 'Requerimento enviado com sucesso!');
    }

    public function show($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        return view('application.show', compact('requerimento'));
    }

    public function edit($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        
        if ($requerimento->email !== Auth::user()->email) {
            return redirect()->route('application.index')
                ->with('error', 'Você não tem permissão para editar este requerimento.');
        }

        return view('application.edit', compact('requerimento'));
    }

    public function update(Request $request, $id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        
        $validatedData = $request->validate([
            'orgaoExpedidor'   => 'required|string|max:50',
            'campus'           => 'required|string|max:255',
            'situacao'         => 'required|in:1,2,3',
            'curso'            => 'required|string|max:255',
            'periodo'          => 'required|in:1,2,3,4,5,6',
            'turno'            => 'required|in:manhã,tarde',
            'observacoes'      => 'nullable|string|max:1000',
            'anexarArquivos'   => 'nullable',
            'anexarArquivos.*' => 'file|mimes:pdf,jpg,png|max:2048',
        ]);

        if ($request->hasFile('anexarArquivos')) {
            if ($requerimento->anexarArquivos) {
                $existingFiles = json_decode($requerimento->anexarArquivos, true);
                if (is_array($existingFiles)) {
                    foreach ($existingFiles as $filePath) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }
            
            $attachmentPaths = [];
            foreach ($request->file('anexarArquivos') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = 'Doc' . ($key + 1) . '.' . $extension;
                $path = $file->storeAs('requerimentos_arquivos', $fileName, 'public');
                $attachmentPaths[] = $path;
            }
            $validatedData['anexarArquivos'] = json_encode($attachmentPaths);
        }

        $validatedData['situacao'] = $this->situacoes[$validatedData['situacao']];

        $requerimento->update($validatedData);
        $requerimento->status = 'em_andamento';
        $requerimento->motivo = null;
        $requerimento->save();

        return redirect()->route('dashboard')
            ->with('success', 'Requerimento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);

        if ($requerimento->anexarArquivos) {
            $existingFiles = json_decode($requerimento->anexarArquivos, true);
            if (is_array($existingFiles)) {
                foreach ($existingFiles as $filePath) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }

        $requerimento->delete();

        return redirect()->route('application.index')
            ->with('success', 'Requerimento excluído com sucesso!');
    }

    public function updateStatus(Request $request, $id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        $requerimento->status = $request->status;

        if (in_array($request->status, ['indeferido', 'pendente'])) {
            $requerimento->motivo = $request->motivo;
        }

        $requerimento->save();

        return redirect()->back()
            ->with('success', 'Status atualizado com sucesso!');
    }
}
