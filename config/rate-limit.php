<?php

return [
    'default' => [
        'rate' => '99999', // Permitir um grande número de solicitações por minuto
        'decay_in_minutes' => 0.01, // Contagem regressiva extremamente rápida
        'burst' => 99999, // Permitir um grande número de solicitações simultâneas
        'max' => null, // Desabilita o bloqueio automático
        'lock_time' => null, // Não bloqueia temporariamente
        'unlimited' => true, // Habilita o acesso ilimitado
    ],

    'guards' => [
        'web' => [
            'rate' => '99999',
            'decay_in_minutes' => 0.01,
            'burst' => 99999,
            'max' => null,
            'lock_time' => null,
            'unlimited' => true,
        ],

        'api' => [
            'rate' => '99999',
            'decay_in_minutes' => 0.01,
            'burst' => 99999,
            'max' => null,
            'lock_time' => null,
            'unlimited' => true,
        ],
    ],
];