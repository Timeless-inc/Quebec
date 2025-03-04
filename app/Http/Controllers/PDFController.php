<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Namespace correto
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

        // Gerar assinatura digital usando a chave UUID
        $assinatura = 'Assinatura Digital Automática - ' . $requerimento->key;

        // Renderizar a view de PDF com os dados do requerimento e a assinatura
        $pdf = Pdf::loadView('pdf.requerimento', [
            'requerimento' => $requerimento,
            'assinatura' => $assinatura,
        ]);

        // Retornar o PDF para download com um nome dinâmico
        return $pdf->download('requerimento_' . $id . '.pdf');
    }

    public function mostrarJustificativa($id)
    {
        $pdfUrl = route('requerimento.pdf', $id);
        return view('justificativa', ['pdfUrl' => $pdfUrl]);
    }
}