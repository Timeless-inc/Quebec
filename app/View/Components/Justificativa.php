<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Justificativa extends Component
{
    public $nome;
    public $matricula;
    public $email;
    public $cpf;
    public $datas;
    public $andamento;
    public $anexos;
    public $observacoes;
    public $id;
    public function __construct($id = null,$nome, $matricula, $email, $cpf, $datas, $andamento, $anexos, $observacoes)
    {
        $this->id = $id ?? uniqid();
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->datas = $datas;
        $this->andamento = $andamento;
        $this->anexos = $anexos;
        $this->observacoes = $observacoes;
    }

    public function render()
    {
        return view('components.justificativa');
    }
}
