<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUploadTotalSize
{
    public function handle(Request $request, Closure $next)
    {
        $totalBytes = 0;
        foreach ($request->allFiles() as $key => $files) {
            if (is_array($files)) {
                foreach ($files as $f) {
                    if ($f && method_exists($f, 'getSize')) {
                        $totalBytes += (int) $f->getSize();
                    }
                }
            } else {
                $f = $files;
                if ($f && method_exists($f, 'getSize')) {
                    $totalBytes += (int) $f->getSize();
                }
            }
        }

        $limitKb = config('validation.file_limits.total_per_request_kb', 10240);
        $limitBytes = $limitKb * 1024;

        if ($totalBytes > $limitBytes) {
            return redirect()->back()->withErrors(['error' => 'O total dos arquivos enviados excede o limite permitido (' . ($limitKb/1024) . ' MB).'])->withInput();
        }

        return $next($request);
    }
}
