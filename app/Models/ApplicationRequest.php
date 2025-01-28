<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRequest extends Model
{
    use HasFactory;

    // Definindo a tabela e os campos do modelo
    protected $table = 'requerimentos';

    // Definindo os campos que podem ser preenchidos (atributos mass assignable)
    protected $fillable = [
        'key', 'nomeCompleto', 'cpf', 'celular', 'email', 'rg', 
        'orgaoExpedidor', 'campus', 'matricula', 'situacao', 'curso', 
        'periodo', 'turno', 'tipoRequisicao', 'anexarArquivos', 'observacoes'
    ];

    // Definindo que o campo 'key' não é incrementado automaticamente
    public $incrementing = false;

    // Definindo que o campo 'key' é do tipo UUID
    protected $keyType = 'string';
}
