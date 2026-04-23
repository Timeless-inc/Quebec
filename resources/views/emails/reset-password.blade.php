<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 min-h-screen bg-gradient-to-br from-slate-100 via-emerald-50 to-slate-200 p-5 font-sans text-slate-700">
    <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-6 py-8 text-center text-white sm:px-10">
            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-md ring-4 ring-white/30">
                <img src="{{ asset('img/logo-sre.png') }}" alt="Logo SRE" class="h-10 w-10 object-contain">
            </div>
            <h1 class="text-2xl font-bold leading-tight sm:text-3xl">Redefinição de Senha</h1>
            <p class="mt-2 text-sm text-emerald-50">Solicitação de segurança da sua conta.</p>
        </div>

        <div class="px-6 py-8 sm:px-10">
            <p class="mb-4 text-base leading-7 text-slate-700">Olá,</p>
            <p class="mb-4 text-base leading-7 text-slate-700">Recebemos uma solicitação para redefinir a senha da sua conta no SRE.</p>
            <p class="mb-6 text-base leading-7 text-slate-700">Para continuar, clique no botão abaixo:</p>

            <div class="mb-6 text-center">
                <a href="{{ $url }}" class="inline-block rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white no-underline hover:bg-emerald-500">Redefinir senha</a>
            </div>

            <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50/70 p-4">
                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Importante</p>
                <p class="text-sm text-slate-700">Este link de redefinição expira em 60 minutos.</p>
                <p class="text-sm text-slate-700">Se você não fez essa solicitação, ignore este e-mail.</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="mb-2 text-sm font-semibold text-slate-700">Problemas no botão?</p>
                <p class="mb-2 text-sm text-slate-600">Copie e cole o link abaixo no navegador:</p>
                <p class="break-all text-xs text-slate-600"><a href="{{ $url }}" class="text-emerald-700 underline">{{ $url }}</a></p>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center sm:px-10">
            <p class="text-xs leading-6 text-slate-500">© {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)</p>
            <p class="text-xs leading-6 text-slate-500">Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>