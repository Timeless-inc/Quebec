<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationRequest;
use App\Models\Event;
use App\Models\Notification;
use App\Models\RequisitionTypeEvent;
use App\Events\ApplicationRequestCreated;
use App\Events\ApplicationStatusChanged;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Guid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\UserLastRequisitionData;

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

        $allRequisitionTypes = $this->tiposRequisicao;

        $tiposDisponiveis = [];
        $tiposIndisponiveis = [];

        foreach ($allRequisitionTypes as $id => $nome) {
            if (in_array($id, $availableTypes)) {
                $tiposDisponiveis[$id] = $nome;
            } else {
                $tiposIndisponiveis[$id] = $nome;
            }
        }

        Log::info('Tipos de requisição para exibição', [
            'disponíveis' => count($tiposDisponiveis),
            'indisponíveis' => count($tiposIndisponiveis),
            'tiposComEventos' => $this->getTiposComEventos()
        ]);

        return view('application.index', [
            'requerimentos' => $requerimentos,
            'cursos' => $cursos,
            'tiposRequisicao' => $tiposDisponiveis,
            'tiposIndisponiveis' => $tiposIndisponiveis,
            'tiposComEventos' => $this->getTiposComEventos(),
            'allTypes' => $allRequisitionTypes
        ]);

        $requerimento = ApplicationRequest::with('forwarding.receiver')->find($id);

        $requerimentos = ApplicationRequest::with(['forwarding', 'forwarding.receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('application.index', compact('requerimentos'));
    }

public function create()
{
    $user = Auth::user();

    // Buscar dados persistidos
    $lastData = UserLastRequisitionData::where('user_id', $user->id)->first();

    $availableTypes = $this->getAvailableRequisitionTypes();
    $tiposRequisicao = collect($this->tiposRequisicao)
        ->filter(function ($value, $key) use ($availableTypes) {
            return in_array($key, $availableTypes);
        })
        ->toArray();

    return view('application.index', [
        'nomeCompleto' => $user->name,
        'matricula'    => $user->matricula,
        'email'        => $user->email,
        'cpf'          => $user->cpf,
        'celular'      => $lastData->celular ?? $user->celular,
        'rg'           => $user->rg,
        'orgaoExpedidor' => $lastData->orgao_expedidor ?? '',
        'campus'       => $lastData->campus ?? '',
        'situacao'     => $lastData->situacao ?? '',
        'curso'        => $lastData->curso ?? '',
        'periodo'      => $lastData->periodo ?? '',
        'turno'        => $lastData->turno ?? '',
        'data'         => now()->format('Y-m-d'),
        'cursos'       => $this->cursos,
        'tiposRequisicao' => $tiposRequisicao,
        'tiposComEventos' => $this->getTiposComEventos(),
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
                'dadosExtra.ano' => 'required_if:tipoRequisicao,6,13,14,19|string|max:4',
                'dadosExtra.semestre' => 'required_if:tipoRequisicao,6,13,14,19|in:1,2',
                'dadosExtra.via' => 'required_if:tipoRequisicao,14|in:1ª via,2ª via',
                'dadosExtra.opcao_reintegracao' => 'required_if:tipoRequisicao,24|in:Reintegração,Estágio,Entrega do Relatório de Estágio,TCC',
                'dadosExtra.componente_curricular' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
                'dadosExtra.nome_professor' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
                'dadosExtra.unidade' => 'required_if:tipoRequisicao,30,31,32|in:1ª unidade,2ª unidade,3ª unidade,4ª unidade,Exame Final',
                'dadosExtra.ano_semestre' => 'required_if:tipoRequisicao,30,31,32|string|max:50',
            ]);

            $tipoId = $validatedData['tipoRequisicao'];
            if (in_array($tipoId, $this->getTiposComEventos())) {
                $eventController = app(EventController::class);
                $events = $eventController->getAvailableEventsForToday();

                $tipoEvents = $events->where('requisition_type_id', $tipoId);

                if ($tipoEvents->isEmpty()) {
                    Log::warning('Tentativa de criar requerimento de tipo que requer evento, mas não há eventos disponíveis hoje', [
                        'tipo_id' => $tipoId,
                        'user_id' => Auth::id()
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Este tipo de requerimento só pode ser criado durante o período do evento associado. Por favor, verifique o calendário de eventos.']);
                }

                Log::info('Evento disponível para criação de requerimento', [
                    'tipo_id' => $tipoId,
                    'eventos_disponíveis' => $tipoEvents->count()
                ]);
            }

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
                'opcao_reintegracao' => null,
                'componente_curricular' => null,
                'nome_professor' => null,
                'unidade' => null,
                'ano_semestre' => null,
            ];
            $dadosExtra = array_merge($defaultDadosExtra, $dadosExtra);
            $validatedData['dadosExtra'] = json_encode($dadosExtra);

            // Usar apenas as observações do usuário
            $validatedData['observacoes'] = $validatedData['observacoes'] ?? '';

            $applicationRequest = ApplicationRequest::create($validatedData);

            if (!isset($applicationRequest->id) || !$applicationRequest->id) {
                Log::info('ID não disponível após criação, buscando pela key', ['key' => $applicationRequest->key]);

                $applicationRequest = ApplicationRequest::where('key', $validatedData['key'])->first();

                if (!$applicationRequest) {
                    Log::error('Não foi possível recuperar o requerimento após criar', ['key' => $validatedData['key']]);
                    throw new \Exception('Erro ao recuperar requerimento após criação');
                }
            }

UserLastRequisitionData::updateOrCreate(
    ['user_id' => Auth::id()],
    [
        'celular'         => $validatedData['celular'],
        'orgao_expedidor' => $validatedData['orgaoExpedidor'],
        'campus'          => $validatedData['campus'],
        'situacao'        => $request->situacao, // Salve o valor numérico
        'curso'           => $request->curso,    // Salve o valor numérico
        'periodo'         => $request->periodo,  // Salve o valor numérico
        'turno'           => $request->turno,    // Salve o valor (manhã/tarde)
    ]
);

            Log::info('Requerimento criado com sucesso', [
                'id' => $applicationRequest->id,
                'key' => $applicationRequest->key
            ]);

            Log::info('Disparando evento ApplicationRequestCreated');
            event(new ApplicationRequestCreated($applicationRequest));
            Log::info('Evento disparado com sucesso');

            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Requerimento Criado',
                'message' => 'Seu requerimento foi enviado com sucesso! Em breve ele será revisado e você será notificado sobre a atualização.',
                'is_read' => false
            ]);

            return redirect()->route('dashboard')
                ->with('notification', [
                    'message' => 'Requerimento enviado com sucesso!',
                    'type' => 'success'
                ]);
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
        $tiposRequisicao = $this->tiposRequisicao;
        $tiposComEventos = $this->getTiposComEventos();

        // Processar anexos existentes
        $anexosAtuais = [];
        if ($requerimento->anexarArquivos) {
            $attachmentPaths = json_decode($requerimento->anexarArquivos, true);
            if (is_array($attachmentPaths)) {
                foreach ($attachmentPaths as $key => $path) {
                    $anexosAtuais[$key] = [
                        'path' => $path,
                        'url' => Storage::url($path),
                        'name' => basename($path)
                    ];
                }
            }
        }

        // Preparar dadosExtra como array
        $dadosExtra = $requerimento->dadosExtra ? json_decode($requerimento->dadosExtra, true) : null;

        return view('application.edit', compact('requerimento', 'cursos', 'tiposRequisicao', 'tiposComEventos', 'anexosAtuais', 'dadosExtra'));
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
            'dadosExtra.ano' => 'required_if:tipoRequisicao,6,13,14,19|string|max:4',
            'dadosExtra.semestre' => 'required_if:tipoRequisicao,6,13,14,19|in:1,2',
            'dadosExtra.via' => 'required_if:tipoRequisicao,14|in:1ª via,2ª via',
            'dadosExtra.opcao_reintegracao' => 'required_if:tipoRequisicao,24|in:Reintegração,Estágio,Entrega do Relatório de Estágio,TCC',
            'dadosExtra.componente_curricular' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
            'dadosExtra.nome_professor' => 'required_if:tipoRequisicao,30,31,32|string|max:255',
            'dadosExtra.unidade' => 'required_if:tipoRequisicao,30,31,32|in:1ª unidade,2ª unidade,3ª unidade,4ª unidade,Exame Final',
            'dadosExtra.ano_semestre' => 'required_if:tipoRequisicao,30,31,32|string|max:50',
        ]);

        $attachmentPaths = $requerimento->anexarArquivos ? json_decode($requerimento->anexarArquivos, true) : [];

        if ($request->hasFile('anexarArquivos')) {
            $counter = 1;
            foreach ($request->file('anexarArquivos') as $key => $file) {
                if ($file) {
                    if (isset($attachmentPaths[$key])) {
                        Storage::disk('public')->delete($attachmentPaths[$key]);
                    }

                    $extension = $file->getClientOriginalExtension();
                    $fileName = "Doc_{$counter}_" . time() . ".{$extension}";
                    $path = $file->storeAs('requerimentos_arquivos', $fileName, 'public');
                    $attachmentPaths[$key] = $path;
                    $counter++;
                }
            }
            $validatedData['anexarArquivos'] = json_encode($attachmentPaths);
        }

        // Processar dadosExtra
        $dadosExtraExistentes = $requerimento->dadosExtra ? json_decode($requerimento->dadosExtra, true) : [];
        $dadosExtraNovos = $request->input('dadosExtra', []);

        $defaultDadosExtra = [
            'ano' => null,
            'semestre' => null,
            'via' => null,
            'opcao_reintegracao' => null,
            'componente_curricular' => null,
            'nome_professor' => null,
            'unidade' => null,
            'ano_semestre' => null,
        ];

        $dadosExtra = array_merge($defaultDadosExtra, $dadosExtraExistentes, $dadosExtraNovos);
        $validatedData['dadosExtra'] = json_encode($dadosExtra);

        // Usar apenas as observações do usuário
        $validatedData['observacoes'] = $validatedData['observacoes'] ?? '';

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
        $olderStatus = $requerimento->status;

        $requerimento->status = $request->status;

        if (in_array($request->status, ['indeferido', 'pendente'])) {
            $requerimento->finalizado_por = Auth::user()->name;

            $requerimento->resolved_at = now();
            $tempoResolucao = $requerimento->created_at->diffInHours($requerimento->resolved_at);
            $requerimento->tempoResolucao = $tempoResolucao;

            if ($request->has('resposta') && !empty($request->resposta)) {
                $requerimento->resposta = $request->resposta;
            }
        }

        if ($request->status === 'finalizado') {
            $requerimento->finalizado_por = Auth::user()->name;

            $requerimento->resolved_at = now();
            $tempoResolucao = $requerimento->created_at->diffInHours($requerimento->resolved_at);
            $requerimento->tempoResolucao = $tempoResolucao;

            if ($request->has('resposta')) {
                $requerimento->resposta = $request->resposta;
            }

            if ($request->hasFile('anexos_finalizacao')) {
                $anexosPaths = [];
                foreach ($request->file('anexos_finalizacao') as $file) {
                    $path = $file->store('anexos_finalizacao', 'public');
                    $anexosPaths[] = $path;
                }
                $requerimento->anexos_finalizacao = json_encode($anexosPaths);
            }
        }

        $newStatus = $requerimento->status;

        $requerimento->save();

        if ($olderStatus !== $newStatus) {
            $statusText = $this->getStatusText($newStatus);

            $user = \App\Models\User::where('email', $requerimento->email)->first();

            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'email' => $requerimento->email,
                    'requerimento_id' => $requerimento->id,
                    'title' => 'Status Atualizado',
                    'message' => "Seu requerimento teve o status atualizado para: {$statusText}!",
                    'is_read' => false
                ]);
            }
        }

        event(new ApplicationStatusChanged($requerimento, $oldStatus, $request->status));

        return redirect()->back()
            ->with('success', 'Status atualizado com sucesso!');
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Análise',
            'finalizado' => 'Aprovado',
            'indeferido' => 'Indeferido'
        ];

        return $statusMap[$status] ?? $status;
    }

    public function getTiposRequisicao()
    {
        return $this->tiposRequisicao;
    }

    public function getTiposComEventos()
    {
        $tiposComEventos = Cache::remember('requisition_types_with_events', 60, function () {
            $requiredTypes = RequisitionTypeEvent::where('requires_event', true)
                ->pluck('requisition_type_id')
                ->toArray();

            return $requiredTypes;
        });

        return $tiposComEventos;
    }

    public function getAvailableRequisitionTypes()
    {
        $user = Auth::user();
        $userId = Auth::id();

        $userIsCradt = (isset($user->role) && $user->role === 'cradt');

        $alwaysAvailableTypes = $this->getTiposRequisicaoSemEvento();

        $allActiveEvents = Event::where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) use ($userId, $userIsCradt) {
                $query->where('is_exception', false);

                if (!$userIsCradt) {
                    $query->orWhere(function ($q) use ($userId) {
                        $q->where('is_exception', true)
                            ->where('exception_user_id', $userId);
                    });
                } else {
                    $query->orWhere('is_exception', true);
                }
            })
            ->get();

        $today = \Carbon\Carbon::today();
        $currentDateEvents = $allActiveEvents->filter(function ($event) use ($today) {
            $eventStartDate = \Carbon\Carbon::parse($event->start_date)->startOfDay();
            $eventEndDate = \Carbon\Carbon::parse($event->end_date)->endOfDay();
            return $today->betweenIncluded($eventStartDate, $eventEndDate);
        });

        $eventDependentTypes = $currentDateEvents
            ->pluck('requisition_type_id')
            ->unique()
            ->toArray();

        $allAvailableTypes = array_merge($alwaysAvailableTypes, $eventDependentTypes);

        return $allAvailableTypes;
    }

    public function getTiposRequisicaoSemEvento()
    {
        $todosOsTipos = array_keys($this->getTiposRequisicao());
        $tiposComEventos = $this->getTiposComEventos();

        return array_diff($todosOsTipos, $tiposComEventos);
    }

    public function isRequisitionTypeAvailable($typeId)
    {
        $availableTypes = $this->getAvailableRequisitionTypes();
        return in_array($typeId, $availableTypes);
    }

    public function getAvailableEvents()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $userIsCradt = $user->role === 'cradt';

        $query = Event::where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) use ($userId, $userIsCradt) {
                $query->where('is_exception', false);

                if (!$userIsCradt) {
                    $query->orWhere(function ($q) use ($userId) {
                        $q->where('is_exception', true)
                            ->where('exception_user_id', $userId);
                    });
                } else {
                    $query->orWhere('is_exception', true);
                }
            });

        return $query->get();
    }

    public function marcarComoVisto($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        $requerimento->visualizado = true;
        $requerimento->save();

        return response()->json(['success' => true]);
    }
}
