<?php

namespace App\Mail\Transport;

use App\Services\GmailOAuth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * Envia os e-mails do Laravel pela API do Gmail (users.messages.send) em vez de SMTP.
 */
class GmailApiTransport extends AbstractTransport
{
    private const ENDPOINT = 'https://gmail.googleapis.com/gmail/v1/users/me/messages/send';

    public function __construct(private GmailOAuth $oauth)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $mime = $message->toString();

        // O Symfony tira o Bcc do MIME; a API do Gmail só entrega para quem está nos cabeçalhos
        $copiasOcultas = MessageConverter::toEmail($message->getOriginalMessage())->getBcc();

        if ($copiasOcultas) {
            $mime = 'Bcc: ' . implode(', ', array_map(fn ($endereco) => $endereco->getAddress(), $copiasOcultas)) . "\r\n" . $mime;
        }

        try {
            $resposta = Http::withToken($this->oauth->tokenDeAcesso())->post(self::ENDPOINT, [
                'raw' => rtrim(strtr(base64_encode($mime), '+/', '-_'), '='),
            ]);
        } catch (\Throwable $e) {
            throw new TransportException('Falha ao enviar pela API do Gmail: ' . $e->getMessage(), 0, $e);
        }

        if ($resposta->failed()) {
            throw new TransportException('A API do Gmail recusou o envio: ' . ($resposta->json('error.message') ?? 'HTTP ' . $resposta->status()));
        }
    }

    public function __toString(): string
    {
        return 'gmail';
    }
}
