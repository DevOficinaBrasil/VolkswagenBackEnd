<?php

namespace App\Strategy;

use App\Interfaces\validationStrategy;
use App\Services\AccessService;
use Illuminate\Http\Request;

class AutoRepairExistValidation implements validationStrategy
{
    public function validate(Request $request, AccessService $service)
    {
        return $service->validateAutoRepairExist($request);
    }
}

class AutoRepairValidation implements ValidationStrategy
{
    public function validate(Request $request, AccessService $service)
    {
        return $service->validateAutoRepair($request);
    }
}
