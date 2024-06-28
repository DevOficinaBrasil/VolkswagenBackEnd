<?php


namespace App\Handlers;

use Illuminate\Http\Request;
use App\Services\AccessService;
use Exception;

class AddressHandler extends AbstractHandler
{
    public function handle(Request $request, AccessService $service, array &$context)
    {
        $address_ID = $service->createAddress($request);

        if (!$address_ID['status']) {
            throw new Exception($address_ID['message']);
        }

        $context['addressId'] = $address_ID['iD'];
        
        return parent::handle($request, $service, $context);
    }
}