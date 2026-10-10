<title>SRE</title>
        <x-bootstrap-styles />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
<script src="{{ asset('js/form-loading.js') }}" defer></script>
<script src="{{ asset('js/filterJustificativas.js') }}"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between header-container">
            <div class="filter-container">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</p>
                <x-status-filter :current="$currentStatus" :options="[
                    'todos' => 'Todos',
                    'em_andamento' => 'Em Aberto',
                    'pendente' => 'Pendente',
                    'deferido' => 'Deferido',
                    'indeferido' => 'Indeferido',
                ]" />
            </div>
            <a href="{{ route('application') }}" class="new-req-btn inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-emerald-700 focus:outline-hidden focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 no-underline hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                Novo requerimento
            </a>
        </div>
    </x-slot>

    <!-- Eventos Acadêmicos Section -->
    @if($events->isNotEmpty())
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2">
                Eventos Acadêmicos:
            </h2>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg p-6">
                <div class="row g-3">
                    @foreach($events as $event)
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card h-100 {{ $event->isEndingToday() ? 'border-danger' : ($event->isExpiringSoon() ? 'border-warning' : '') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                </div>
                                
                                <p class="card-text">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                </p>
                                
                                @if($event->isEndingToday())
                                <div class="alert alert-danger py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> Evento próximo de encerramento
                                </div>
                                @elseif($event->daysUntilExpiration() == 1)
                                <div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-clock"></i> Este evento vai encerrar em 1 dia
                                </div>
                                @elseif($event->daysUntilExpiration() > 1 && $event->daysUntilExpiration() <= 3)
                                <div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-clock"></i> Este evento vai encerrar em {{ $event->daysUntilExpiration() }} dias
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <x-event-edit :event="$event" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Processos Section -->
    <div class="pb-6 pt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2">
                {{ $currentStatus === 'pendente' ? 'Processos em Atenção:' : 
                ($currentStatus === 'deferido' ? 'Processos Deferidos:' : 
                ($currentStatus === 'indeferido' ? 'Processos Indeferidos:' :
                ($currentStatus === 'em_aberto' ? 'Processos em Aberto:' :
                ($currentStatus === 'em_andamento' ? 'Processos em Aberto:' : 'Todos os Processos:')))) }}
            </h2>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($requerimentos->count() > 0)
            @foreach($requerimentos as $requerimento)
            @php
                $dadosExtra = $requerimento->dadosExtra ? json_decode($requerimento->dadosExtra, true) : [];
            @endphp
            <x-justificativa-aluno
                id="{{ $requerimento->id }}"
                nome="{{ $requerimento->nomeCompleto }}"
                matricula="{{ $requerimento->matricula }}"
                email="{{ $requerimento->email }}"
                cpf="{{ $requerimento->cpf }}"
                datas="{{ $requerimento->created_at->format('d/m/Y') }}"
                status="{{ $requerimento->status }}"
                :anexos="[$requerimento->anexarArquivos]"
                observacoes="{{ $requerimento->observacoes }}"
                resposta="{{ $requerimento->resposta }}"
                :anexos_finalizacao="[$requerimento->anexos_finalizacao]"
                tipoRequisicao="{{ $requerimento->tipoRequisicao }}"
                :dadosExtra="$dadosExtra"
                class="justificativa-item mb-4"
            />
            @endforeach
            @else
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg p-6 text-center">
                <p class="mb-3">Você ainda não possui nenhum requerimento. Comece agora mesmo!</p>
                <a href="{{ route('application') }}" class="btn btn-success">Fazer Requerimento</a>
            </div>
            @endif
        </div>
        
        @if($requerimentos->hasPages())
        <div class="flex justify-center mt-6">
            <div class="bg-white p-3 rounded-lg shadow-md w-full max-w-2xl">
                {{ $requerimentos->appends(['status' => $currentStatus ?? 'todos'])->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
        
    </div>
</x-app-layout>

<script src="{{ asset('js/filterJustificativas.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status') || 'todos';
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.dataset.status === status) {
                btn.classList.add('font-bold');
                btn.style.opacity = '1';
            } else {
                btn.style.opacity = '0.8';
            }
        });
        
        document.querySelectorAll('.pagination a').forEach(link => {
            if (link.href) {
                const url = new URL(link.href, window.location.origin);
                url.searchParams.set('status', status);
                link.href = url.toString();
            }
        });
    });
</script>

<style>
    .card {
        transition: transform 0.2s;
        height: 100%;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .filter-btn {
        transition: all 0.2s;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .filter-container {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 6px;
            margin-top: 10px;
            padding: 0;
        }
        
        .filter-btn {
            margin: 3px !important;
            font-size: 0.7rem !important;
            padding: 4px 6px !important;
            white-space: nowrap;
        }
        
        .header-container {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .new-req-btn {
            margin-top: 15px !important;
            width: 100%;
        }
        
        .new-req-btn button {
            width: 100%;
            padding: 8px !important;
            font-size: 0.9rem !important;
        }
        
        .card-title {
            font-size: 1rem;
        }
        
        .alert {
            font-size: 0.8rem;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }
        
        .pagination .page-item {
            margin: 2px;
        }
        
        .max-w-7xl {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        
        .py-6 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;  
        }
        
        .p-6 {
            padding: 1rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .filter-btn {
            font-size: 0.65rem !important;
            padding: 3px 5px !important;
        }
        
        h2.text-xl {
            font-size: 1.1rem !important;
            margin-bottom: 10px !important;
        }
    }
</style>
