<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\ApplicationRequest;

class JustificativaAluno extends Component
{
    public $id;
    public $nome;
    public $matricula;
    public $email;
    public $cpf;
    public $datas;
    public $andamento;
    public $anexos;
    public $observacoes;
    public $tipoRequisicao; 
    public $key;
    public $requerimentos; // Adiciona a variável para os requerimentos
    public $status;

    /**
     * O construtor do componente.
     */
    public function __construct($id = null)
    {
        // Se um id for fornecido, busca o requerimento específico
        if ($id) {
            try {
                $requerimento = ApplicationRequest::findOrFail($id);
                $this->id = $requerimento->id;
                $this->nome = $requerimento->nomeCompleto;
                $this->matricula = $requerimento->matricula;
                $this->email = $requerimento->email;
                $this->cpf = $requerimento->cpf;
                $this->datas = $requerimento->created_at->toDateString(); 
                $this->andamento = $requerimento->situacao;
                $this->anexos = json_decode($requerimento->anexarArquivos); 
                $this->observacoes = $requerimento->observacoes;
                $this->tipoRequisicao = $requerimento->tipoRequisicao;
                $this->key = $requerimento->key;
                $this->requerimentos = $requerimento->requerimentos;
                $this->status = $requerimento->status;
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $this->id = $id ?: uniqid();
                $this->nome = 'Não encontrado';
                $this->matricula = 'Não encontrado';
                $this->email = 'Não encontrado';
                $this->cpf = 'Não encontrado';
                $this->datas = 'Não encontrado';
                $this->andamento = 'Não encontrado';
                $this->anexos = [];
                $this->observacoes = 'Não encontrado';
                $this->tipoRequisicao = 'Não encontrado';
                $this->key = 'Não encontrado';
            }
        }

        // Busca todos os requerimentos da tabela
        $this->requerimentos = ApplicationRequest::all(); // Recupera todos os registros da tabela

    }

    /**
     * Renderiza a view do componente.
     */
    public function render()
    {
        return view('components.justificativa-aluno');
    }
}

