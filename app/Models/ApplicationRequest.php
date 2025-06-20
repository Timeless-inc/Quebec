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
        'key',
        'nomeCompleto',
        'cpf',
        'celular',
        'email',
        'rg',
        'orgaoExpedidor',
        'campus',
        'matricula',
        'situacao',
        'curso',
        'periodo',
        'turno',
        'tipoRequisicao',
        'anexarArquivos',
        'observacoes',
        'status',
        'motivo',
        'dadosExtra',
        'resolved_at',
        'tempoResolucao'
    ];

    protected $casts = [
        'anexarArquivos' => 'array',
        'dadosExtra' => 'array', // Garante que dadosExtra seja tratado como array
    ];

    protected $attributes = [
        'status' => 'em_andamento',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key)) {
                $model->key = (string) Str::uuid();
            }
        });
    }

    public function forwarding()
    {
        return $this->hasOne(RequestForwarding::class, 'requerimento_id')
            ->where('is_returned', true) 
            ->latest();  
    }
}
