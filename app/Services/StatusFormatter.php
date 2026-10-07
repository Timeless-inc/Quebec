<?php

namespace App\Services;

class StatusFormatter
{
    private static array $statusMap = [
        'pendente' => 'Pendente',
        'em_andamento' => 'Em Análise',
        'encaminhado' => 'Encaminhado',
        'devolvido' => 'Devolvido',
        'finalizado' => 'Finalizado',
        'indeferido' => 'Indeferido',
        //'deferido' => 'Deferido',
    ];

    public static function format(string $status): string
    {
        $cleanStatus = trim(strtolower($status));
        
        return self::$statusMap[$cleanStatus] 
            ?? ucfirst(str_replace('_', ' ', $cleanStatus));
    }

    public static function getAll(): array
    {
        return self::$statusMap;
    }
}