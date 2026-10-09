<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Autorização OAuth da conta que envia os e-mails do sistema pela API do Gmail.
 *
 * As credenciais do cliente (JSON baixado do Google Cloud) e o refresh token gerado por
 * "php artisan gmail:authorize" ficam fora do repositório, nos caminhos de config/services.php.
 */
class GmailOAuth
{
    private const ESCOPO = 'https://www.googleapis.com/auth/gmail.send';
    private const CACHE_TOKEN = 'gmail_api_access_token';

    public function urlDeAutorizacao(): string
    {
        $credenciais = $this->credenciais();

        return $credenciais['auth_uri'] . '?' . http_build_query([
            'client_id' => $credenciais['client_id'],
            'redirect_uri' => $this->redirectUri($credenciais),
            'response_type' => 'code',
            'scope' => self::ESCOPO,
            // offline + consent garantem que o Google devolva o refresh token
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);
    }

    /**
     * Troca o código devolvido pelo Google pelo refresh token e o guarda no arquivo de token.
     */
    public function trocarCodigo(string $codigo): void
    {
        $credenciais = $this->credenciais();

        $resposta = Http::asForm()->post($credenciais['token_uri'], [
            'code' => $codigo,
            'client_id' => $credenciais['client_id'],
            'client_secret' => $credenciais['client_secret'],
            'redirect_uri' => $this->redirectUri($credenciais),
            'grant_type' => 'authorization_code',
        ]);

        if ($resposta->failed() || !$resposta->json('refresh_token')) {
            throw new RuntimeException('O Google não autorizou a conta: ' . $this->erro($resposta));
        }

        File::ensureDirectoryExists(dirname($this->caminhoDoToken()));
        File::put($this->caminhoDoToken(), json_encode(['refresh_token' => $resposta->json('refresh_token')], JSON_PRETTY_PRINT));

        Cache::forget(self::CACHE_TOKEN);
    }

    public function autorizado(): bool
    {
        return File::exists($this->caminhoDoToken());
    }

    /**
     * Token de acesso válido, renovado com o refresh token quando o anterior expira.
     */
    public function tokenDeAcesso(): string
    {
        if ($token = Cache::get(self::CACHE_TOKEN)) {
            return $token;
        }

        if (!$this->autorizado()) {
            throw new RuntimeException('A conta do Gmail ainda não foi autorizada. Rode "php artisan gmail:authorize".');
        }

        $credenciais = $this->credenciais();
        $refreshToken = json_decode(File::get($this->caminhoDoToken()), true)['refresh_token'] ?? null;

        $resposta = Http::asForm()->post($credenciais['token_uri'], [
            'client_id' => $credenciais['client_id'],
            'client_secret' => $credenciais['client_secret'],
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($resposta->failed() || !$resposta->json('access_token')) {
            throw new RuntimeException('Não foi possível renovar o acesso ao Gmail: ' . $this->erro($resposta));
        }

        Cache::put(self::CACHE_TOKEN, $resposta->json('access_token'), max((int) $resposta->json('expires_in') - 60, 60));

        return $resposta->json('access_token');
    }

    private function credenciais(): array
    {
        $caminho = $this->resolver(config('services.gmail.credentials'));

        if (!$caminho || !File::exists($caminho)) {
            throw new RuntimeException('Arquivo de credenciais do Gmail não encontrado. Confira GMAIL_CREDENTIALS_PATH no .env.');
        }

        $json = json_decode(File::get($caminho), true);

        // O JSON do Google vem dentro de "installed" (app de computador) ou "web"
        return $json['installed'] ?? $json['web'] ?? throw new RuntimeException('Arquivo de credenciais do Gmail em formato inesperado.');
    }

    private function redirectUri(array $credenciais): string
    {
        return $credenciais['redirect_uris'][0] ?? 'http://localhost';
    }

    private function caminhoDoToken(): string
    {
        return $this->resolver(config('services.gmail.token'));
    }

    private function resolver(?string $caminho): ?string
    {
        if (!$caminho) {
            return null;
        }

        return preg_match('/^([A-Za-z]:|[\/\\\\])/', $caminho) ? $caminho : base_path($caminho);
    }

    private function erro($resposta): string
    {
        return $resposta->json('error_description') ?? $resposta->json('error') ?? ('HTTP ' . $resposta->status());
    }
}
