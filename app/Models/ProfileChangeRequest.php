<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'field',
        'current_value',
        'new_value',
        'document_path',
        'status',
        'admin_comment',
        'request_group_id'
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_NEEDS_REVIEW = 'needs_review';

    // Únicos campos do usuário que um pedido de alteração pode modificar
    const CAMPOS_ALTERAVEIS = ['name', 'matricula', 'matricula_adicional', 'cpf', 'rg'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Grava no usuário a alteração solicitada.
     */
    public function aplicar(): void
    {
        abort_unless(in_array($this->field, self::CAMPOS_ALTERAVEIS, true), 422, 'Campo não permitido para alteração de perfil.');

        $user = $this->user;

        if ($this->field !== 'matricula_adicional') {
            $user->{$this->field} = $this->new_value;
            $user->save();

            return;
        }

        // Sem valor atual é inclusão; sem valor novo é remoção; com os dois é troca
        if ($this->current_value) {
            $user->matriculasAdicionais()->where('matricula', $this->current_value)->delete();
        }

        if ($this->new_value) {
            $user->matriculasAdicionais()->firstOrCreate(['matricula' => $this->new_value]);
        }
    }
}