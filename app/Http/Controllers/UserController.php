<?php

namespace App\Http\Controllers;

use App\Http\Repository\AddressRepository;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AddressService;
use App\Services\AutoRepairService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected AddressService $addressService,
        protected AutoRepairService $autoRepairService,
        protected AddressRepository $addressRepo
    ){}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userData = $this->service->allInfos($id);

        if($userData){
            return response()->json($userData, 200);
        }

        return response()->json('usuário não encontrado', 404);
    }

    public function getAllUserInfo(string $id)
    {
        $user = $this->service->allInfos($id);
        $addressData = $this->addressService->getAddress($user->common_user_address);
        $autoRepair = $this->autoRepairService->getInfosAutoRepairByID($user->id);

        $userData = [
            'user'=>$user,
            'adressUser'=>$addressData,
            'autoRepar'=>$autoRepair
        ];
        if($userData){
            return response()->json($userData, 200);
        }

        return response()->json('usuário não encontrado', 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $this->service->updateUser($request); 
        
        // error_log($data);
        
        if($data){
            return response()->json($data, 200);
        }

        return response()->json('usuário não encontrado', 404);
        // return response()->json($request, 200);
    }


    public function updateUserAddress(Request $request)
    {
        $state_ID = $this->addressService->ifExistState($request->state);

        $city_ID  = $this->addressService->ifExistCity($request->city, $state_ID);

        $request['city_id'] = $city_ID;
        
        $data = $this->addressRepo->update($request); 
        
        // // error_log($data);
        
        if($data){
            return response()->json($data, 200);
        }

        return response()->json('Endereço não encontrado', 404);
        // return response()->json($request, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
