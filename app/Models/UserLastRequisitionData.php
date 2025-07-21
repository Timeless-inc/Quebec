<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLastRequisitionData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome_completo',
        'email',
        'cpf',
        'rg',
        'orgao_expedidor',
        'celular',
        'matricula',
        'campus',
        'curso',
        'situacao',
        'periodo',
        'turno',
        'tipo_requisicao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
