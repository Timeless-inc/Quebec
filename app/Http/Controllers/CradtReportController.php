<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 

class CradtReportController extends Controller
{
    private $mapeamentoCampos = [
        'tipoRequisicao' => 'tipo',
        'finalizado_por' => 'responsavel',
        'status' => 'status',
        'turno' => 'turno',
        'curso' => 'curso'
    ];

    private $filtrosDisponiveis = [
        'tipo' => 'tipoRequisicao',
        'status' => 'status',
        'turno' => 'turno',
        'curso' => 'curso',
        'responsavel' => 'finalizado_por',
        'tempoResolucao' => 'tempoResolucao'
    ];

    public function index()
    {
        //$user = Auth::user();
        //if (!$user) {
        //    abort(403, 'Acesso negado.');
        //}
        //Gate::authorize('isCradt', $user);

        $requerimentos = ApplicationRequest::select('situacao', DB::raw('count(*) as total'))
            ->groupBy('situacao')
            ->orderByDesc('total')
            ->get();

        $requerimentosTipo = ApplicationRequest::select('tipoRequisicao', DB::raw('count(*) as total'))
            ->groupBy('tipoRequisicao')
            ->orderByDesc('total')
            ->get();

        $requerimentosStatus = ApplicationRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $requerimentosTurnos = ApplicationRequest::select('turno', DB::raw('count(*) as total'))
            ->groupBy('turno')
            ->orderByDesc('total')
            ->get();

        $requerimentosCursos = ApplicationRequest::select('curso', DB::raw('count(*) as total'))
            ->groupBy('curso')
            ->orderByDesc('total')
            ->get();

        $anosDisponiveis = DB::table('requerimentos')
            ->select(DB::raw('YEAR(created_at) as ano'))
            ->distinct()
            ->orderBy('ano')
            ->pluck('ano')
            ->toArray();

        if (empty($anosDisponiveis)) {
            $anosDisponiveis = [date('Y')];
        }

        $filtrosDisponiveis = array_keys($this->filtrosDisponiveis);

        return view('cradt-report.index', compact(
            'requerimentos',
            'requerimentosTipo',
            'requerimentosStatus',
            'requerimentosTurnos',
            'requerimentosCursos',
            'anosDisponiveis',
            'filtrosDisponiveis'
        ));
    }

    public function getFilteredData(Request $request)
    {
        $mes = $request->input('mes');
        $ano = $request->input('ano');
        $filtro = $request->input('filtro');
        $filtroExtra = $request->input('filtroExtra');
        $valorFiltroExtra = $request->input('valorFiltroExtra');

        $query = ApplicationRequest::query();

        if ($mes === 'all') {
            $startDate = Carbon::createFromDate($ano, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($ano, 12, 31)->endOfYear();
        } else {
            $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        if ($filtroExtra && $valorFiltroExtra) {
            $query = $this->aplicarFiltroSecundario($query, $filtroExtra, $valorFiltroExtra);
        }

        $data = $this->obterDadosPorFiltro($query, $filtro, $request);

        return response()->json($data);
    }

    private function aplicarFiltroSecundario($query, $filtroExtra, $valorFiltroExtra)
    {
        $filtroExtra = $this->mapearFiltroParaColuna($filtroExtra);

        if ($filtroExtra === 'finalizado_por') {
            $query->whereNotNull('finalizado_por')
                  ->where('finalizado_por', '!=', '')
                  ->where('finalizado_por', '!=', 'null')
                  ->where('finalizado_por', '!=', 'N/A')
                  ->where('finalizado_por', '!=', 'Não Atribuído')
                  ->where('finalizado_por', $valorFiltroExtra);
        } else {
            $query->where($filtroExtra, $valorFiltroExtra);
        }

        return $query;
    }

    private function obterDadosPorFiltro($query, $filtro, Request $request)
    {
        $filtroColuna = $this->mapearFiltroParaColuna($filtro);

        if ($filtro === 'tempoResolucao') {
            return $query->whereNotNull('resolved_at')
                ->select(
                    DB::raw('CASE 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 24 THEN "Menos de 1 dia"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 48 THEN "1 dia" 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 72 THEN "2 dias"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 168 THEN "3-7 dias"
                        ELSE "Mais de 7 dias"
                    END as label'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('label')
                ->orderBy(DB::raw('CASE 
                    WHEN label = "Menos de 1 dia" THEN 1
                    WHEN label = "1 dia" THEN 2
                    WHEN label = "2 dias" THEN 3
                    WHEN label = "3-7 dias" THEN 4
                    ELSE 5
                END'))
                ->get();
        } elseif ($filtro === 'combinado') {
            $primaryField = $this->mapearFiltroParaColuna($request->input('primaryField', 'tipo'));
            $secondaryField = $this->mapearFiltroParaColuna($request->input('secondaryField', 'curso'));

            return $query->select(
                    DB::raw("CONCAT({$primaryField}, ' - ', {$secondaryField}) as label"),
                    DB::raw('count(*) as total')
                )
                ->whereNotNull($primaryField)
                ->whereNotNull($secondaryField)
                ->where($primaryField, '!=', '')
                ->where($secondaryField, '!=', '')
                ->when($primaryField === 'finalizado_por' || $secondaryField === 'finalizado_por', function($q) {
                    return $q->where('finalizado_por', '!=', 'null')
                             ->where('finalizado_por', '!=', 'N/A')
                             ->where('finalizado_por', '!=', 'Não Atribuído');
                })
                ->groupBy($primaryField, $secondaryField)
                ->orderByDesc('total')
                ->get();
        } else {
            return $query->select($filtroColuna . ' as label', DB::raw('count(*) as total'))
                ->whereNotNull($filtroColuna)
                ->where($filtroColuna, '!=', '')
                ->when($filtroColuna === 'finalizado_por', function($q) {
                    return $q->where('finalizado_por', '!=', 'null')
                             ->where('finalizado_por', '!=', 'N/A')
                             ->where('finalizado_por', '!=', 'Não Atribuído');
                })
                ->groupBy($filtroColuna)
                ->orderByDesc('total')
                ->get();
        }
    }

    public function getDistinctValues(Request $request)
    {
        $campo = $request->input('campo');
        $campoMapeado = $this->mapearFiltroParaColuna($campo);
        $colunaPermitida = in_array($campoMapeado, [
            'tipoRequisicao', 'status', 'turno', 'curso', 'finalizado_por', 'tempoResolucao'
        ]);

        if (!$colunaPermitida) {
            return response()->json(['error' => 'Campo não permitido'], 400);
        }

        $valores = ApplicationRequest::select($campoMapeado)
            ->distinct()
            ->whereNotNull($campoMapeado)
            ->where($campoMapeado, '!=', '')
            ->when($campoMapeado === 'finalizado_por', function($query) {
                return $query->where('finalizado_por', '!=', 'null')
                             ->where('finalizado_por', '!=', 'N/A')
                             ->where('finalizado_por', '!=', 'Não Atribuído');
            })
            ->orderBy($campoMapeado)
            ->pluck($campoMapeado);

        return response()->json($valores);
    }

    private function mapearFiltroParaColuna($filtro)
    {
        return isset($this->filtrosDisponiveis[$filtro]) ? $this->filtrosDisponiveis[$filtro] : $filtro;
    }

    public function getCrossReport(Request $request)
    {
        try {
            $mes = $request->input('mes');
            $ano = $request->input('ano');
            $filtro1 = $request->input('filtro1');
            $filtro2 = $request->input('filtro2');
            $filtro1Coluna = $this->mapearFiltroParaColuna($filtro1);
            $filtro2Coluna = $this->mapearFiltroParaColuna($filtro2);
            $filtrosPermitidos = array_values($this->filtrosDisponiveis);

            if (!in_array($filtro1Coluna, $filtrosPermitidos) || !in_array($filtro2Coluna, $filtrosPermitidos)) {
                return response()->json(['error' => 'Filtros inválidos'], 400);
            }

            if ($filtro1Coluna === $filtro2Coluna) {
                return response()->json(['error' => 'Os filtros não podem ser iguais'], 400);
            }

            if ($filtro1Coluna === 'tempoResolucao' || $filtro2Coluna === 'tempoResolucao') {
                return $this->getCrossReportWithTimeResolution($request, $filtro1Coluna, $filtro2Coluna, $mes, $ano);
            }

            $campo1 = isset($this->mapeamentoCampos[$filtro1Coluna]) ? $this->mapeamentoCampos[$filtro1Coluna] : $filtro1Coluna;
            $campo2 = isset($this->mapeamentoCampos[$filtro2Coluna]) ? $this->mapeamentoCampos[$filtro2Coluna] : $filtro2Coluna;

            $query = ApplicationRequest::query();

            if ($mes === 'all') {
                $startDate = Carbon::createFromDate($ano, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($ano, 12, 31)->endOfYear();
            } else {
                $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
            }

            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotNull($filtro1Coluna)
                  ->whereNotNull($filtro2Coluna)
                  ->where($filtro1Coluna, '!=', '')
                  ->where($filtro2Coluna, '!=', '');

            if ($filtro1Coluna === 'finalizado_por' || $filtro2Coluna === 'finalizado_por') {
                $query->where('finalizado_por', '!=', 'null')
                      ->where('finalizado_por', '!=', 'N/A')
                      ->where('finalizado_por', '!=', 'Não Atribuído');
            }

            $resultados = $query->select(
                    $filtro1Coluna . ' as ' . $campo1,
                    $filtro2Coluna . ' as ' . $campo2,
                    DB::raw('count(*) as total')
                )
                ->groupBy($filtro1Coluna, $filtro2Coluna)
                ->orderBy($filtro1Coluna)
                ->orderBy($filtro2Coluna)
                ->get();

            if ($resultados->isEmpty()) {
                return response()->json([
                    'rotulos' => [],
                    'categorias' => [],
                    'dados' => [],
                    'titulo' => 'Relatório Cruzado: ' . ucfirst($campo1) . ' x ' . ucfirst($campo2)
                ]);
            }

            $dadosCruzados = [
                'rotulos' => $resultados->pluck($campo2)->unique()->values(),
                'categorias' => $resultados->pluck($campo1)->unique()->values(),
                'dados' => [],
                'titulo' => 'Relatório Cruzado: ' . ucfirst($campo1) . ' x ' . ucfirst($campo2)
            ];

            foreach ($dadosCruzados['categorias'] as $categoria) {
                $linha = [];
                foreach ($dadosCruzados['rotulos'] as $rotulo) {
                    $valor = $resultados->first(function ($item) use ($categoria, $rotulo, $campo1, $campo2) {
                        return $item->$campo1 === $categoria && $item->$campo2 === $rotulo;
                    });
                    $linha[] = $valor ? $valor->total : 0;
                }
                $dadosCruzados['dados'][] = $linha;
            }

            return response()->json($dadosCruzados);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar relatório cruzado: ' . $e->getMessage()], 500);
        }
    }

    private function getCrossReportWithTimeResolution(Request $request, $filtro1, $filtro2, $mes, $ano)
    {
        try {
            $tempoFiltro = ($filtro1 === 'tempoResolucao') ? 'tempoResolucao' : 'tempoResolucao';
            $outroFiltro = ($filtro1 === 'tempoResolucao') ? $filtro2 : $filtro1;
            $outroNome = isset($this->mapeamentoCampos[$outroFiltro]) ? $this->mapeamentoCampos[$outroFiltro] : $outroFiltro;

            $query = ApplicationRequest::query();

            if ($mes === 'all') {
                $startDate = Carbon::createFromDate($ano, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($ano, 12, 31)->endOfYear();
            } else {
                $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
            }

            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotNull('resolved_at')
                  ->whereNotNull($outroFiltro)
                  ->where($outroFiltro, '!=', '');

            if ($outroFiltro === 'finalizado_por') {
                $query->where('finalizado_por', '!=', 'null')
                      ->where('finalizado_por', '!=', 'N/A')
                      ->where('finalizado_por', '!=', 'Não Atribuído');
            }

            $resultados = $query->select(
                    $outroFiltro . ' as categoria',
                    DB::raw('CASE 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 24 THEN "Menos de 1 dia"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 48 THEN "1 dia" 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 72 THEN "2 dias"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 168 THEN "3-7 dias"
                        ELSE "Mais de 7 dias"
                    END as tempo'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('categoria', 'tempo')
                ->orderBy('categoria')
                ->get();

            if ($resultados->isEmpty()) {
                return response()->json([
                    'rotulos' => [],
                    'categorias' => [],
                    'dados' => [],
                    'titulo' => 'Relatório Cruzado: ' . ucfirst($outroNome) . ' x Tempo de Resolução'
                ]);
            }

            $temposOrdenados = [
                "Menos de 1 dia",
                "1 dia",
                "2 dias",
                "3-7 dias",
                "Mais de 7 dias"
            ];

            $dadosCruzados = [
                'rotulos' => $temposOrdenados,
                'categorias' => $resultados->pluck('categoria')->unique()->values(),
                'dados' => [],
                'titulo' => 'Relatório Cruzado: ' . ucfirst($outroNome) . ' x Tempo de Resolução'
            ];

            foreach ($dadosCruzados['categorias'] as $categoria) {
                $linha = [];
                foreach ($temposOrdenados as $tempo) {
                    $valor = $resultados->first(function ($item) use ($categoria, $tempo) {
                        return $item->categoria === $categoria && $item->tempo === $tempo;
                    });
                    $linha[] = $valor ? $valor->total : 0;
                }
                $dadosCruzados['dados'][] = $linha;
            }

            return response()->json($dadosCruzados);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar relatório cruzado com tempo: ' . $e->getMessage()], 500);
        }
    }
    public function generatePdf(Request $request)
    {
        $mes = $request->input('mes');
        $ano = $request->input('ano');
        $filtro = $request->input('filtro');
        $filtroExtra = $request->input('filtroExtra');
        $valorFiltroExtra = $request->input('valorFiltroExtra');
        
        $query = ApplicationRequest::query();
        
        if ($mes === 'all') {
            $startDate = Carbon::createFromDate($ano, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($ano, 12, 31)->endOfYear();
            $periodoTexto = "Ano de $ano";
        } else {
            $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
            $periodoTexto = "Mês " . $startDate->format('m/Y');
        }
        
        $query->whereBetween('created_at', [$startDate, $endDate]);
        
        if ($filtroExtra && $valorFiltroExtra) {
            $query = $this->aplicarFiltroSecundario($query, $filtroExtra, $valorFiltroExtra);
            $filtroExtraTexto = $this->getFilterName($filtroExtra) . ": " . $valorFiltroExtra;
        } else {
            $filtroExtraTexto = "";
        }
        
        if ($filtro === 'tempoResolucao') {
            $data = $query->whereNotNull('resolved_at')
                ->select(
                    DB::raw('CASE 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 24 THEN "Menos de 1 dia"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 48 THEN "1 dia" 
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 72 THEN "2 dias"
                        WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 168 THEN "3-7 dias"
                        ELSE "Mais de 7 dias"
                    END as label'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('label')
                ->orderBy(DB::raw('CASE 
                    WHEN label = "Menos de 1 dia" THEN 1
                    WHEN label = "1 dia" THEN 2
                    WHEN label = "2 dias" THEN 3
                    WHEN label = "3-7 dias" THEN 4
                    ELSE 5
                END'))
                ->get();
            
            $tituloRelatorio = "Relatório de Tempo de Resolução";
        } else {
            $filtroColuna = $this->mapearFiltroParaColuna($filtro);
            
            $data = $query->select($filtroColuna . ' as label', DB::raw('count(*) as total'))
                ->whereNotNull($filtroColuna)
                ->where($filtroColuna, '!=', '')
                ->when($filtroColuna === 'finalizado_por', function($q) {
                    return $q->where('finalizado_por', '!=', 'null')
                            ->where('finalizado_por', '!=', 'N/A')
                            ->where('finalizado_por', '!=', 'Não Atribuído');
                })
                ->groupBy($filtroColuna)
                ->orderByDesc('total')
                ->get();
            
            $tituloRelatorio = "Relatório por " . $this->getFilterName($filtro);
        }
        
        $totalRequerimentos = $data->sum('total');
        
        $pdf = PDF::loadView('cradt-report.pdf', [
            'titulo' => $tituloRelatorio,
            'periodo' => $periodoTexto,
            'filtroExtra' => $filtroExtraTexto,
            'data' => $data,
            'totalRequerimentos' => $totalRequerimentos,
            'dataGeracao' => now()->format('d/m/Y H:i')
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('relatorio-sre-' . now()->format('dmY-His') . '.pdf');
    }

    public function generateUserPdf(Request $request)
    {
        $user = Auth::user();
        
        $period = $request->input('period', 'mes-atual');
        
        switch ($period) {
            case 'mes-atual':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodoTexto = 'Mês Atual: ' . $startDate->format('m/Y');
                break;
                
            case 'mes-anterior':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $periodoTexto = 'Mês Anterior: ' . $startDate->format('m/Y');
                break;
                
            case 'ano-atual':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $periodoTexto = 'Ano Atual: ' . $startDate->format('Y');
                break;
                
            case 'personalizado':
                $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
                $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
                $periodoTexto = 'Período: ' . $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');
                break;
                
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodoTexto = 'Mês Atual: ' . $startDate->format('m/Y');
        }
        
        $userData = [];
        
        $userData['totalAtendimentos'] = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->count();
        
        $tempoMedioQuery = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereNotNull('resolved_at')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as tempo_medio'))
            ->first();
        
        $userData['tempoMedioHoras'] = round($tempoMedioQuery->tempo_medio ?? 0, 1);
        $userData['tempoMedioDias'] = round(($tempoMedioQuery->tempo_medio ?? 0) / 24, 1);
        
        $userData['ultimosAtendimentos'] = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->orderBy('resolved_at', 'desc')
            ->limit(5)
            ->get(['id', 'tipoRequisicao', 'created_at', 'resolved_at', 'status']);
        
        $userData['tiposFrequentes'] = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->select('tipoRequisicao', DB::raw('count(*) as total'))
            ->groupBy('tipoRequisicao')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $userData['situacoesFrequentes'] = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
        
        $userData['estatisticasPeriodicas'] = [];
        
        if ($period === 'ano-atual') {
            for ($i = 1; $i <= 12; $i++) {
                $mesInicio = Carbon::createFromDate($startDate->year, $i, 1)->startOfMonth();
                $mesFim = Carbon::createFromDate($startDate->year, $i, 1)->endOfMonth();
                
                $total = ApplicationRequest::where('finalizado_por', $user->name)
                    ->whereBetween('resolved_at', [$mesInicio, $mesFim])
                    ->count();
                
                $userData['estatisticasPeriodicas'][] = [
                    'label' => $mesInicio->format('M/Y'),
                    'total' => $total
                ];
            }
        } else if ($period === 'personalizado' && $startDate->diffInDays($endDate) > 31) {
            $currentDate = Carbon::parse($startDate);
            
            while ($currentDate->lte($endDate)) {
                $mesInicio = Carbon::parse($currentDate)->startOfMonth();
                $mesFim = Carbon::parse($currentDate)->endOfMonth();
                
                if ($mesFim->gt($endDate)) {
                    $mesFim = Carbon::parse($endDate);
                }
                
                $total = ApplicationRequest::where('finalizado_por', $user->name)
                    ->whereBetween('resolved_at', [$mesInicio, $mesFim])
                    ->count();
                
                $userData['estatisticasPeriodicas'][] = [
                    'label' => $mesInicio->format('M/Y'),
                    'total' => $total
                ];
                
                $currentDate->addMonth();
            }
        } else {
            $currentDate = Carbon::parse($startDate);
            
            while ($currentDate->lte($endDate)) {
                $total = ApplicationRequest::where('finalizado_por', $user->name)
                    ->whereDate('resolved_at', $currentDate)
                    ->count();
                
                $userData['estatisticasPeriodicas'][] = [
                    'label' => $currentDate->format('d/m'),
                    'total' => $total
                ];
                
                $currentDate->addDay();
            }
        }
        
        $userData['resolucaoEficiente'] = ApplicationRequest::where('finalizado_por', $user->name)
            ->whereNotNull('resolved_at')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 24 THEN "Menos de 1 dia"
                    WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 48 THEN "1 dia" 
                    WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 72 THEN "2 dias"
                    WHEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) < 168 THEN "3-7 dias"
                    ELSE "Mais de 7 dias"
                END as faixa_tempo'),
                DB::raw('count(*) as total')
            )
            ->groupBy('faixa_tempo')
            ->orderBy(DB::raw('CASE 
                WHEN faixa_tempo = "Menos de 1 dia" THEN 1
                WHEN faixa_tempo = "1 dia" THEN 2
                WHEN faixa_tempo = "2 dias" THEN 3
                WHEN faixa_tempo = "3-7 dias" THEN 4
                ELSE 5
            END'))
            ->get();
        
        $userData['estatisticasMensais'] = $userData['estatisticasPeriodicas'] ?? [];
        
        $pdf = PDF::loadView('cradt-report.user-pdf', [
            'user' => $user,
            'data' => $userData,
            'periodoTexto' => $periodoTexto,
            'dataGeracao' => now()->format('d/m/Y H:i')
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('meu-relatorio-' . now()->format('dmY-His') . '.pdf');
    }

    private function getFilterName($filtro)
    {
        $nomes = [
            'tipo' => 'Tipo de Requisição',
            'status' => 'Status',
            'turno' => 'Turno',
            'curso' => 'Curso',
            'responsavel' => 'Responsável',
            'tempoResolucao' => 'Tempo de Resolução'
        ];
        
        return $nomes[$filtro] ?? ucfirst($filtro);
    }
}