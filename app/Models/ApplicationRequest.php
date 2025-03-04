<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRequest extends Model
{
    use HasFactory;

    protected $table = 'requerimentos';

    protected $fillable = [
        'key', 'nomeCompleto', 'cpf', 'celular', 'email', 'rg', 
        'orgaoExpedidor', 'campus', 'matricula', 'situacao', 'curso', 
        'periodo', 'turno', 'tipoRequisicao', 'anexarArquivos', 'observacoes',
        'dadosExtra'
    ];

    protected $casts = [
        'dadosExtra' => 'array', // Garante que dadosExtra seja tratado como array
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}