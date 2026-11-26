<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationRequest;
use Illuminate\Support\Str;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PDFController extends Controller
{
    public function gerarPDF($id)
    {
        $requerimento = ApplicationRequest::findOrFail($id);
        $requerimento->dadosExtra = json_decode($requerimento->dadosExtra, true);

        $urlVerificacao = route('requerimento.verificar', $requerimento->key);
        $qrCode = (new QRCode(new QROptions(['outputInterface' => QRGdImagePNG::class, 'scale' => 6])))->render($urlVerificacao);

        $pdf = Pdf::loadView('pdf.requerimento', [
            'requerimento' => $requerimento,
            'urlVerificacao' => $urlVerificacao,
            'qrCode' => $qrCode,
        ]);

        return $pdf->download('requerimento_' . $id . '.pdf');
    }

    // Rota pública (aberta pelo QR do PDF): só dados mascarados, para conferir com o papel.
    public function verificar(string $key)
    {
        $requerimento = ApplicationRequest::where('key', $key)->firstOrFail();

        $nomePartes = preg_split('/\s+/', trim($requerimento->nomeCompleto));
        $nomeMascarado = collect($nomePartes)
            ->map(fn ($parte, $posicao) => $posicao === 0 ? $parte : mb_strtoupper(mb_substr($parte, 0, 1)) . '.')
            ->implode(' ');

        return response()->view('requerimento-verificacao', [
            'requerimento' => $requerimento,
            'nomeMascarado' => $nomeMascarado,
            'matriculaMascarada' => Str::mask((string) $requerimento->matricula, '*', 0, -4),
            'statusTexto' => ApplicationRequest::STATUS_TEXTOS[$requerimento->status] ?? $requerimento->status,
        ])->withHeaders([
            'X-Robots-Tag' => 'noindex, nofollow',
            'Referrer-Policy' => 'no-referrer',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    public function mostrarJustificativa($id)
    {
        $pdfUrl = route('requerimento.pdf', $id);
        return view('justificativa', ['pdfUrl' => $pdfUrl]);
    }
}