<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'matricula',
        'rg_uf',
        'cpf',
        'rg',
        'password',
        'role',
        'profile_photo_path'       
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function matriculasAdicionais()
    {
        return $this->hasMany(UserMatricula::class);
    }

    /**
     * Matrícula principal seguida das adicionais.
     */
    public function todasMatriculas(): array
    {
        return array_values(array_filter([
            $this->matricula,
            ...$this->matriculasAdicionais->pluck('matricula'),
        ]));
    }

    /**
     * Indica se a matrícula já pertence a algum usuário, como principal ou adicional.
     */
    public static function matriculaEmUso(string $matricula, ?int $ignorarUserId = null): bool
    {
        return static::where('matricula', $matricula)
                ->when($ignorarUserId, fn ($query) => $query->where('id', '!=', $ignorarUserId))
                ->exists()
            || UserMatricula::where('matricula', $matricula)
                ->when($ignorarUserId, fn ($query) => $query->where('user_id', '!=', $ignorarUserId))
                ->exists();
    }

    /**
     * Substitui as matrículas adicionais do usuário pela lista informada.
     */
    public function sincronizarMatriculasAdicionais(array $matriculas): void
    {
        $matriculas = array_values(array_unique(array_filter(array_map('trim', $matriculas))));

        $this->matriculasAdicionais()->whereNotIn('matricula', $matriculas)->delete();

        foreach ($matriculas as $matricula) {
            $this->matriculasAdicionais()->firstOrCreate(['matricula' => $matricula]);
        }

        $this->unsetRelation('matriculasAdicionais');
    }

    public function isDiretorGeral(): bool
    {
        return $this->role === 'Diretor Geral';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['Cradt', 'Diretor Geral']);
    }

    public function canReceiveForwardings(): bool
    {
        if ($this->role === 'Diretor Geral') {
            return true;
        }
        return \App\Models\Role::where('label', $this->role)
            ->where('can_receive_forwardings', true)
            ->exists();
    }

    public function getRouteSlug(): string
    {
        return \Illuminate\Support\Str::slug($this->role);
    }

    public function getDashboardRoute(): string
    {
        if (in_array($this->role, ['Cradt'])) {
            return route('cradt');
        }
        if ($this->isDiretorGeral()) {
            return route('diretor-geral.dashboard');
        }
        if ($this->role === 'Aluno') {
            return route('dashboard');
        }
        
        $slug = $this->getRouteSlug();
        if (\App\Models\Role::where('label', $this->role)->exists()) {
            return route('painel.dashboard', ['cargoSlug' => $slug]);
        }

        return route('dashboard'); 
    }
}

