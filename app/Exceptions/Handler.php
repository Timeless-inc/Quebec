<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle PostTooLargeException
        $this->renderable(function (PostTooLargeException $e, $request) {
            return redirect()->back()
                ->withErrors([
                    'upload_error' => 'O tamanho total dos arquivos enviados excede o limite permitido (máximo 20MB por requisição). Reduza o número de arquivos ou seus tamanhos.'
                ])
                ->withInput();
        });
    }
}
