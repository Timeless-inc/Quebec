<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApplicationRequest extends Model
{
    use HasFactory;

    protected $table = 'requerimentos';

    protected $fillable = [
        'key', 'nomeCompleto', 'cpf', 'celular', 'email', 'rg', 
        'orgaoExpedidor', 'campus', 'matricula', 'situacao', 'curso', 
        'periodo', 'turno', 'tipoRequisicao', 'anexarArquivos', 'observacoes',
        'status', 'motivo'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $casts = [
        'anexarArquivos' => 'array',
    ];
    
    protected $attributes = [
        'status' => 'em_andamento',
    ];

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key)) {
                $model->key = (string) Str::uuid();
            }
        });
    }
}