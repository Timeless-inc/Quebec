<?php

namespace App\View\Components;

use App\Models\ApplicationRequest;
use Illuminate\View\Component;

class Justificativa extends Component
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
    public $tipoRequisicao;  // Propriedade tipoRequisicao
    public $key;  // Propriedade key

    /**
     * O construtor do componente.
     */
    public function __construct($id = null)
    {
        // Verificar se o ID foi fornecido e buscar os dados do banco
        if ($id) {
            $this->id = $id;
            $requerimento = ApplicationRequest::find($id);

            // Se o requerimento não for encontrado, lançar um erro
            if ($requerimento) {
                $this->id = $requerimento->$id;
                $this->nome = $requerimento->nomeCompleto;
                $this->matricula = $requerimento->matricula;
                $this->email = $requerimento->email;
                $this->cpf = $requerimento->cpf;
                $this->datas = $requerimento->created_at->toDateString();  // Exemplo de data
                $this->andamento = $requerimento->situacao;  // Substituir pela lógica de andamento
                $this->anexos = explode(",", $requerimento->anexarArquivos); // Caso os arquivos estejam separados por vírgula
                $this->observacoes = $requerimento->observacoes;
                $this->tipoRequisicao = $requerimento->tipoRequisicao;  // Busca o tipoRequisicao
                $this->key = $requerimento->key;  // Busca o campo key
            } else {
                // Caso não encontre o requerimento, retornamos valores padrão ou um erro
                $this->id = $id ?: uniqid();
                $this->nome = 'Não encontrado';
                $this->matricula = 'Não encontrado';
                $this->email = 'Não encontrado';
                $this->cpf = 'Não encontrado';
                $this->datas = 'Não encontrado';
                $this->andamento = 'Não encontrado';
                $this->anexos = [];
                $this->observacoes = 'Não encontrado';
                $this->tipoRequisicao = 'Não encontrado';  // Valor padrão
                $this->key = 'Não encontrado';  // Valor padrão
            }
        }
    }

    /**
     * Renderiza a view do componente.
     */
    public function render()
    {
        return view('components.justificativa');
    }
}


