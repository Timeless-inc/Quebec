<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ValidateUploadFile implements Rule
{
    protected $reason = null;
    protected $overrides = [];

    public function __construct()
    {
        // aceitar overrides opcionais (ex: ['pdf' => 5120])
        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            $this->overrides = $args[0];
        }
    }

    public function passes($attribute, $value)
    {
        if (!($value instanceof UploadedFile)) {
            return false;
        }

        $mime = $value->getClientMimeType();
        $sizeKb = (int) ceil($value->getSize() / 1024);

        $pdfLimit = $this->overrides['pdf'] ?? config('validation.file_limits.pdf', 2048);
        $imageLimit = $this->overrides['image'] ?? config('validation.file_limits.image_upload_max_kb', 5120);
        $maxPixels = config('validation.file_limits.image_max_pixels', 20000000);

        if (str_starts_with($mime, 'image/')) {
            // quick pixel check to avoid exhausting memory when processing huge images
            try {
                $info = @getimagesize($value->getRealPath());
                if ($info && isset($info[0]) && isset($info[1])) {
                    $pixels = $info[0] * $info[1];
                    if ($pixels > $maxPixels) {
                        $this->reason = sprintf('A imagem "%s" tem dimensão muito grande (%d x %d = %d pixels). Máx: %d pixels.', $value->getClientOriginalName(), $info[0], $info[1], $pixels, $maxPixels);
                        return false;
                    }
                }
            } catch (\Throwable $e) {
                // if getimagesize fails, fall back to size-based check
            }
            if ($sizeKb > $imageLimit) {
                $this->reason = sprintf('A imagem "%s" excede o limite de %d KB (tamanho atual: %d KB).', $value->getClientOriginalName(), $imageLimit, $sizeKb);
                return false;
            }
            return true;
        }

        if ($mime === 'application/pdf' || $value->getClientOriginalExtension() === 'pdf') {
            return $sizeKb <= $pdfLimit;
        }

        // For other allowed mime types use the larger limit as fallback
        $allowed = explode(',', config('validation.allowed_mimes', 'pdf,jpg,jpeg,png'));
        $ext = strtolower($value->getClientOriginalExtension());
        if (in_array($ext, $allowed, true)) {
            if ($sizeKb > $imageLimit) {
                $this->reason = sprintf('O arquivo "%s" excede o limite de %d KB (tamanho atual: %d KB).', $value->getClientOriginalName(), $imageLimit, $sizeKb);
                return false;
            }
            return true;
        }

        return false;
    }

    public function message()
    {
        return $this->reason ?? 'O arquivo excede os limites permitidos ou o tipo não é suportado.';
    }
}
