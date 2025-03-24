<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationRequest;
use App\Events\ApplicationRequestCreated;
use App\Events\ApplicationStatusChanged;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Guid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

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
        30 => 'Lançamento de Nota',
        31 => 'Revisão de Notas',
        32 => 'Revisão de Faltas',
        33 => 'Tempo de escolaridade',
    ];

    private $situacoes = [
        1 => 'Matriculado',
        2 => 'Graduado',
        3 => 'Desvinculado',
    ];

    private $cursos = [
        1 => 'Administração',
        2 => 'Sistemas para Internet',
        3 => 'Logística',
        4 => 'Gestão de Qualidade',
        5 => 'Informatica para Internet',
    ];

    private $tiposComEventos = [
        2,
        20,
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

        $cursos = $this->cursos;

        $availableTypes = $this->getAvailableRequisitionTypes();
        $tiposRequisicao = collect($this->tiposRequisicao)
            ->filter(function ($value, $key) use ($availableTypes) {
                return in_array($key, $availableTypes);
            })
            ->toArray();

        return view('application.index', [
            'requerimentos' => $requerimentos,
            'cursos' => $cursos,
            'tiposRequisicao' => $tiposRequisicao,
            'tiposComEventos' => $this->tiposComEventos
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        $availableTypes = $this->getAvailableRequisitionTypes();
        $tiposRequisicao = collect($this->tiposRequisicao)
            ->filter(function ($value, $key) use ($availableTypes) {
                return in_array($key, $availableTypes);
            })
            ->toArray();

        return view('application.create', [
            'nomeCompleto' => $user->name,
            'matricula'    => $user->matricula,
            'email'        => $user->email,
            'cpf'          => $user->cpf,
            'data'         => now()->format('Y-m-d'),
            'cursos'       => $this->cursos,
            'tiposRequisicao' => $tiposRequisicao,
            'tiposComEventos' => $this->tiposComEventos,
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Iniciando criação de requerimento');

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
                'curso'            => 'required|in:1,2,3,4,5',
                'periodo'          => 'required|in:1,2,3,4,5,6,7,8',
                'turno'            => 'required|in:manhã,tarde',
                'tipoRequisicao' => 'required|integer|in:' . implode(',', array_keys($this->tiposRequisicao)),
                'anexarArquivos.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                'observacoes'      => 'nullable|string|max:1000',
                'dadosExtra.ano' => 'required_if:tipoRequisicao,6,13,14,19|string|max:4', // Removido 24
                'dadosExtra.semestre' => 'required_if:tipoRequisicao,6,13,14,19|in:1,2', // Removido 24
                'dadosExtra.via' => 'required_if:tipoRequisicao,14|in:1ª via,2ª via',
                'dadosExtra.opcao_reintegracao' => 'required_if:tipoRequisicao,24|in:Reintegração,Estágio,Entrega do Relatório de Estágio,TCC',
                'dadosExtra.componente_curricular' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
                'dadosExtra.nome_professor' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
                'dadosExtra.unidade' => 'required_if:tipoRequisicao,30,31,32|in:1ª unidade,2ª unidade,3ª unidade,4ª unidade,Exame Final',
                'dadosExtra.ano_semestre' => 'required_if:tipoRequisicao,30,31,32|string|max:50',
            ]);

            $validatedData['tipoRequisicao'] = $this->tiposRequisicao[$validatedData['tipoRequisicao']];
            $validatedData['situacao'] = $this->situacoes[$validatedData['situacao']];
            $validatedData['key'] = Guid::uuid4()->toString();
            $validatedData['curso'] = $this->cursos[$validatedData['curso']];
            $validatedData['status'] = 'em_andamento';

            Log::info('Dados validados com sucesso, processando arquivos');

            // Processar anexos
            $attachmentPaths = [];
            if ($request->hasFile('anexarArquivos')) {
                foreach ($request->file('anexarArquivos') as $key => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = "Doc_{$key}_" . time() . ".{$extension}";
                    $path = $file->storeAs('requerimentos_arquivos', $fileName, 'public');
                    $attachmentPaths[$key] = $path;
                }
            }
            $validatedData['anexarArquivos'] = !empty($attachmentPaths) ? json_encode($attachmentPaths) : null;

            // Garantir que dadosExtra seja salvo como JSON com todas as chaves possíveis
            $dadosExtra = $request->input('dadosExtra', []);
            $defaultDadosExtra = [
                'ano' => null,
                'semestre' => null,
                'via' => null,
                'opcao_reintegracao' => null, // Apenas o campo necessário para o tipo 24
                'componente_curricular' => null,
                'nome_professor' => null,
                'unidade' => null,
                'ano_semestre' => null,
            ];
            $dadosExtra = array_merge($defaultDadosExtra, $dadosExtra);
            $validatedData['dadosExtra'] = json_encode($dadosExtra);

            // Adicionar informações dinâmicas ao campo observações para tipos 6, 13, 14, 19, 24
            $observacoesDinamicas = '';
            $tipoId = array_search($validatedData['tipoRequisicao'], $this->tiposRequisicao);
            if (in_array($tipoId, [6, 13, 14, 19, 24])) {
                $ano = $dadosExtra['ano'] ?? 'Não informado';
                $semestre = $dadosExtra['semestre'] ?? 'Não informado';
                $via = $dadosExtra['via'] ?? 'Não informado';
                $opcaoReintegracao = $dadosExtra['opcao_reintegracao'] ?? 'Não informado';

                switch ($tipoId) {
                    case 6: // Certificado de Conclusão
                        $observacoesDinamicas = "Certificado de Conclusão - Ano: $ano, Semestre: $semestre";
                        break;
                    case 13: // Declaração para Estágio
                        $observacoesDinamicas = "Declaração para Estágio - Ano: $ano, Semestre: $semestre";
                        break;
                    case 14: // Diploma 1ªvia/2ªvia
                        $observacoesDinamicas = "Diploma - $via - Ano: $ano, Semestre: $semestre";
                        break;
                    case 19: // Histórico Escolar
                        $observacoesDinamicas = "Histórico Escolar - Ano: $ano, Semestre: $semestre";
                        break;
                    case 24: // Reintegração
                        $observacoesDinamicas = "Reintegração - $opcaoReintegracao"; // Removido Ano e Semestre
                        break;
                }
            }

            // Concatenar observações dinâmicas e do usuário sem formatação adicional
            $observacoesUsuario = $validatedData['observacoes'] ?? '';
            $validatedData['observacoes'] = $observacoesDinamicas . ($observacoesUsuario ? "\n\n" . $observacoesUsuario : '');

            $applicationRequest = ApplicationRequest::create($validatedData);

            if (!isset($applicationRequest->id) || !$applicationRequest->id) {
                Log::info('ID não disponível após criação, buscando pela key', ['key' => $applicationRequest->key]);

                $applicationRequest = ApplicationRequest::where('key', $validatedData['key'])->first();

                if (!$applicationRequest) {
                    Log::error('Não foi possível recuperar o requerimento após criar', ['key' => $validatedData['key']]);
                    throw new \Exception('Erro ao recuperar requerimento após criação');
                }
            }

            Log::info('Requerimento criado com sucesso', [
                'id' => $applicationRequest->id,
                'key' => $applicationRequest->key
            ]);

            Log::info('Disparando evento ApplicationRequestCreated');
            event(new ApplicationRequestCreated($applicationRequest));
            Log::info('Evento disparado com sucesso');

            return redirect()->route('application.success')->with('success', 'Requerimento enviado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao processar requerimento', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao processar requerimento. Por favor, tente novamente.']);
        }
    }

    public function success()
    {
        return view('application.success');
    }

    public function show($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);

        // Processar anexos para exibição
        $anexos = [];
        if ($requerimento->anexarArquivos) {
            $attachmentPaths = json_decode($requerimento->anexarArquivos, true);

            if (is_array($attachmentPaths)) {
                foreach ($attachmentPaths as $key => $path) {
                    $anexos[] = [
                        'path' => $path,
                        'url' => Storage::url($path),
                        'name' => basename($path)
                    ];

                    // Log para debug
                    Log::info('Anexo preparado para exibição', [
                        'path' => $path,
                        'url' => Storage::url($path),
                        'exists' => Storage::disk('public')->exists($path)
                    ]);
                }
            }
        }

        return view('application.show', compact('requerimento', 'anexos'));
    }

    public function edit($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);

        if ($requerimento->email !== Auth::user()->email) {
            return redirect()->route('application.index')
                ->with('error', 'Você não tem permissão para editar este requerimento.');
        }

        $cursos = $this->cursos;

        return view('application.edit', compact('requerimento', 'cursos'));
    }

    public function update(Request $request, $id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);

        $validatedData = $request->validate([
            'orgaoExpedidor'   => 'required|string|max:50',
            'campus'           => 'required|string|max:255',
            'situacao'         => 'required|in:1,2,3',
            'curso'            => 'required|in:1,2,3,4,5',
            'periodo'          => 'required|in:1,2,3,4,5,6,7,8',
            'turno'            => 'required|in:manhã,tarde',
            'observacoes'      => 'nullable|string|max:1000',
            'anexarArquivos.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
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
                $fileName = 'Doc_' . ($key + 1) . '_' . time() . '.' . $extension;
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
        $oldStatus = $requerimento->status;

        $requerimento->status = $request->status;

        if (in_array($request->status, ['indeferido', 'pendente'])) {
            $requerimento->motivo = $request->motivo;
        }

        if ($request->status === 'finalizado') {
            $requerimento->finalizado_por = Auth::user()->name;
        }

        $requerimento->save();

        // Evento de atualização de status do requerimento - envio de email para o aluno - passível de ser modificado
        event(new ApplicationStatusChanged($requerimento, $oldStatus, $request->status));

        return redirect()->back()
            ->with('success', 'Status atualizado com sucesso!');
    }

    public function getTiposRequisicao()
    {
        return $this->tiposRequisicao;
    }

    public function getTiposComEventos()
    {
        return $this->tiposComEventos;
    }

    public function getAvailableRequisitionTypes()
    {
        $eventTypes = Cache::get('event_requisition_types', []);

        $allTypes = array_keys($this->tiposRequisicao);


        return array_filter($allTypes, function ($typeId) use ($eventTypes) {
            if (in_array($typeId, $this->tiposComEventos)) {
                return in_array($typeId, $eventTypes);
            }

            return true;
        });
    }

    public function isRequisitionTypeAvailable($typeId)
    {
        $availableTypes = $this->getAvailableRequisitionTypes();
        return in_array($typeId, $availableTypes);
    }
}
