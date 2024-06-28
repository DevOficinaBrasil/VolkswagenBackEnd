<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use App\Services\AccessService;
use Exception;

class AccessHandler extends AbstractHandler
{
    public function handle(Request $request, AccessService $service, array &$context)
    {
        $user = $this->createOrUpdateUser($request, $context['situation'], $context['addressId'], $context['singlePassId'], $service);

        if(!$user['status']){
            throw new Exception($user['message']);
        }

        $context['userId'] = $user['iD'];
        
        return parent::handle($request, $service, $context);
    }

    private function createOrUpdateUser(Request $request, $situation, $addressID, $singlePassID, $service)
    {
        if ($situation) {
            return $service->createUser($request, $addressID, $singlePassID);
        }

        return $service->updateUser($request, $addressID, $singlePassID);
    }
}