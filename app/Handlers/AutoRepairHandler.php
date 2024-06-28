<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use App\Services\AccessService;
use App\Traits\NormalizeRequest;
use Exception;

class AutoRepairHandler extends AbstractHandler
{
    use NormalizeRequest;
    
    public function handle(Request $request, AccessService $service, array &$context)
    {
        if($request->check && !$request->exist){
            $this->processAutoRepair($request, $context['userId'], $service);
        }
    }

    private function processAutoRepair(Request $request, $userID, $service)
    {
        $normalizedRequest = $this->normalizeRequestData($request);

        $addressAutoRepairID = $service->createAddress($normalizedRequest);

        $autoRepair = $service->createAutoRepair($normalizedRequest, $addressAutoRepairID['iD']);

        $verify = $service->link_AutoRepair_User($normalizedRequest, $userID, $autoRepair);

        if (!$verify['status']) {
            throw new Exception($verify['message']);
        }
    }
}