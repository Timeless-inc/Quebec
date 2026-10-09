<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class PrimeiroAcessoCodigoMail extends Mailable
{
    public $user;
    public $codigo;
    public $minutos;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $codigo, int $minutos)
    {
        $this->user = $user;
        $this->codigo = $codigo;
        $this->minutos = $minutos;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Seu código de primeiro acesso ao SRE')
                    ->view('emails.primeiro-acesso-codigo');
    }
}
