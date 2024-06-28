<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait NormalizeRequest
{
    public function normalizeRequestData(Request $request)
    {
        $fields = [
            'cnpj',
            'fantasy_name',
            'branch_activity',
            'auto_repair_state',
            'auto_repair_city',
            'auto_repair_cep',
            'auto_repair_phone',
            'auto_repair_street',
            'auto_repair_number',
            'role',
        ];

        $filtered_request = $request->only($fields);

        $mapping = [
            'state'  => 'auto_repair_state',
            'city'   => 'auto_repair_city',
            'cep'    => 'auto_repair_cep',
            'street' => 'auto_repair_street',
            'number' => 'auto_repair_number'
        ];

        foreach ($mapping as $standard => $alternative) {
            if ($request->has($alternative)) {
                $filtered_request[$standard] = $request->get($alternative);
            }
        }
        
        return new Request($filtered_request);
    }
}