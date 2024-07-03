<?php

namespace App\Http\Controllers;

use App\Interfaces\Handler;
use Illuminate\Http\Request;
use App\Handlers\AccessHandler;
use App\Services\AccessService;
use App\Handlers\AddressHandler;
use App\Traits\NormalizeRequest;
use App\Adapter\RdStationAdapter;
use App\Handlers\AutoRepairHandler;
use App\Handlers\SinglePassHandler;
use App\Events\SendNotificationEvent;
use App\Handlers\ValidateUserHandler;
use App\Handlers\ValidateSituationHandler;
use App\Traits\GenerateParamsNotification;
use App\Handlers\ValidateAutoRepairHandler;
use Illuminate\Support\Facades\DB;

class AccessController extends Controller
{
    use NormalizeRequest, GenerateParamsNotification;

    private Handler $handler;

    public function __construct(
        protected AccessService $service
    ){
        $this->handler = new ValidateUserHandler();

        $validateSituationHandler  = new ValidateSituationHandler();

        $validateAutoRepairHandler = new ValidateAutoRepairHandler();

        $singlePassHandler         = new SinglePassHandler();

        $addressHandler            = new AddressHandler();

        $accessHandler             = new AccessHandler();

        $autoRepairHandler         = new AutoRepairHandler();

        $this->handler             ->setNext($validateSituationHandler);
        $validateSituationHandler  ->setNext($validateAutoRepairHandler);
        $validateAutoRepairHandler ->setNext($singlePassHandler);
        $singlePassHandler         ->setNext($addressHandler);
        $addressHandler            ->setNext($accessHandler);
        $accessHandler             ->setNext($autoRepairHandler);
    }
    
    /**
     * 
     * Controller geral
     */
    public function signup(Request $request)
    {
        $context = [];

        DB::beginTransaction();

        try{
            $this->handler->handle($request, $this->service, $context);
            
            $params = $this->registerUser($request);
    
            SendNotificationEvent::dispatch($request->email, $params);

            DB::commit();
    
            return response()->json([
                'token'   => $context['singlePassToken'],
                'user_id' => $context['singlePassId'],
                'idUser' => $context['userId'],
                'message' => 'Usuário cadastrado com sucesso',
            ], 201);
        }catch(\Exception $error){
            DB::rollBack();

            return response()->json([
                'message' => $error
            ], 500);
        }
    }
}
