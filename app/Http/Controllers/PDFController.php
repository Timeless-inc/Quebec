<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Namespace do DOMPDF
use App\Models\ApplicationRequest;

class PDFController extends Controller
{
    /**
     * Gera o PDF para o requerimento específico.
     *
     * @param int $id ID do requerimento
     * @return \Illuminate\Http\Response PDF para download
     */
    public function gerarPDF($id)
    {
        // Buscar os dados do requerimento pelo ID
        $requerimento = ApplicationRequest::findOrFail($id);

        // Retornar o PDF para download com um nome dinâmico
        return $this->montarPdf($requerimento)->download('requerimento_' . $id . '.pdf');
    }

    /**
     * Tela para conferir a assinatura digital impressa no PDF de um requerimento.
     */
    public function verificarAssinatura(Request $request)
    {
        $codigo = trim((string) $request->query('codigo'));
        $requerimento = null;

        if ($codigo !== '') {
            // Aceita o hash puro ou a linha inteira copiada do PDF
            $chave = Str::match('/[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}/i', $codigo);
            $requerimento = $chave ? ApplicationRequest::where('key', $chave)->first() : null;
        }

        return view('assinatura.verificar', compact('codigo', 'requerimento'));
    }

    /**
     * Exibe (ou baixa, com ?download=1) o PDF do requerimento dono da assinatura.
     */
    public function pdfPorAssinatura(Request $request, $chave)
    {
        $requerimento = ApplicationRequest::where('key', $chave)->firstOrFail();
        $pdf = $this->montarPdf($requerimento);
        $nomeArquivo = 'requerimento_' . $requerimento->id . '.pdf';

        return $request->boolean('download') ? $pdf->download($nomeArquivo) : $pdf->stream($nomeArquivo);
    }

    private function montarPdf(ApplicationRequest $requerimento)
    {
        // Converter dadosExtra de JSON para array manualmente
        $requerimento->dadosExtra = json_decode($requerimento->dadosExtra, true);

        // Gerar assinatura digital usando a chave UUID
        $assinatura = 'Assinatura Digital Automática - ' . $requerimento->key;

        // Renderizar a view de PDF com os dados do requerimento e a assinatura
        return Pdf::loadView('pdf.requerimento', [
            'requerimento' => $requerimento,
            'assinatura' => $assinatura,
        ]);
    }

    public function mostrarJustificativa($id)
    {
        $pdfUrl = route('requerimento.pdf', $id);
        return view('justificativa', ['pdfUrl' => $pdfUrl]);
    }
}
