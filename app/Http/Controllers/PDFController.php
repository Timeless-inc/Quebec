<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\ApplicationRequest;
use Dompdf\Options;

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

        // Gerar assinatura digital usando CPF, nome e e-mail
        $assinatura = hash('sha256', $requerimento->cpf . $requerimento->nome . $requerimento->email);

        // Configurar as opções do DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isJavascriptEnabled', true);

        // Renderizar a view de PDF com os dados do requerimento e a assinatura
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.requerimento', [
            'requerimento' => $requerimento,
            'assinatura' => $assinatura,
        ]);

        $data = [
            'image_path' => storage_path('logo-instituto.png'),
        ];

        // Retornar o PDF para download com um nome dinâmico
        return $pdf->download('requerimento_' . $id . '.pdf');
    }

    public function mostrarJustificativa($id)
{
    $pdfUrl = route('requerimento.pdf', $id);
    return view('justificativa', ['pdfUrl' => $pdfUrl]);
}
}
