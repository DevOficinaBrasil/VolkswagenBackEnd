<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use App\Services\AccessService;
use Exception;

class ValidateUserHandler extends AbstractHandler
{
    public function handle(Request $request, AccessService $service, array &$context)
    {
        $validateUser = $service->validateUser($request);

        if (!$validateUser['status']) {
            throw new Exception($validateUser['message']);
        }

        return parent::handle($request, $service, $context);
    }
}