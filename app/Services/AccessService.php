<?php

namespace App\Services;

use Exception;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\AutoRepairRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RuntimeException;

class AccessService
{
    public function __construct(
        protected AddressService $address,
        protected SinglePassService $singlePass,
        protected UserRepositoryInterface $userRepo,
        protected AutoRepairRepositoryInterface $autoRepairRepo
    ){}
    
    public function validateUser(Request $request): array
    {
        try{
            $rules = [
                'cep'      => 'required|min:8',
                'name'     => 'required',
                'city'     => 'required',
                'phone'    => 'required|min:11',
                'email'    => 'required|email',
                'state'    => 'required',
                'street'   => 'required',
                'gender'   => 'required',
                'number'   => 'required',
                'document' => 'required|min:11', // |unique:App\Models\User,document
                'born_at'  => 'required|date|date_format:Y-m-d|before:today|after:1900-01-01',
                'password' => 'required|min:6',
            ];

            $alias = [
                'cep'      => 'CEP',
                'document' => 'CPF',
                'born_at'  => 'data de nascimento',
            ];

            $message = [
                'born_at.after' => 'O campo data de nascimento deve ser uma data posterior a 01 de Janeiro de 1900.',
            ];
            
            $request->validate($rules, $message, $alias);

            return [
                'status' => true
            ];
        }catch(Exception $error){
            return [
                'status'  => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function validateAutoRepair(Request $request)
    {
        try{
            $rules = [
                'cnpj'               => 'required|min:14|unique:App\Models\AutoRepair,CNPJ',
                'role'               => 'required',
                'fantasy_name'       => 'required',
                'branch_activity'    => 'required',
                'auto_repair_cep'    => 'required|min:8',
                'auto_repair_city'   => 'required',
                'auto_repair_state'  => 'required',
                'auto_repair_phone'  => 'required',
                'auto_repair_street' => 'required',
                'auto_repair_number' => 'required',
            ];

            $alias = [
                'cnpj'               => 'CPNJ',
                'role'               => 'cargo',
                'fantasy_name'       => 'nome fantasia',
                'branch_activity'    => 'ramo de atividade',
                'auto_repair_cep'    => 'CEP',
                'auto_repair_city'   => 'cidade',
                'auto_repair_state'  => 'estado',
                'auto_repair_phone'  => 'telefone',
                'auto_repair_street' => 'rua',
                'auto_repair_number' => 'número',
            ];

            $request->validate($rules, [], $alias);
        }catch(Exception $error){
            return [
                'status'  => false,
                'message' => $error->getMessage()
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function validateAutoRepairExist(Request $request)
    {
        try{
            $rules = [
                'role' => 'required',
            ];

            $alias = [
                'role' => 'cargo',
            ];
            
            $request->validate($rules, [], $alias);
        }catch(Exception $error){
            return [
                'status'  => false,
                'message' => $error->getMessage()
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function verifySituation(Request $request)
    {
        $data = $this->userRepo->search('document', $request->document);
        
        if($data->isEmpty()){
            return 0;
        }else if($data->first()->user_login_id == null){
            return 1;
        }else{
            return 2;
        }
    }

    public function createAddress(Request $request)
    {
        $state_ID = $this->address->ifExistState($request->state);

        $city_ID  = $this->address->ifExistCity($request->city, $state_ID);

        $data = [
            'cep'        => $request->cep,
            'street'     => $request->street,
            'number'     => $request->number,
            'complement' => $request->complement,
        ];

        try{
            $address_ID = $this->address->addAddress($data, $city_ID);
        }catch(QueryException $error){
            return [
                'status'  => false,
                'message' => 'Erro ao salvar o endereço'
            ];
        }

        return [
            'status'  => true,
            'iD' => $address_ID
        ];
    }

    public function singePassRequest(Request $request){
        $data_SinglePass = [
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'common',
            'password' => $request->password,
        ];

        try{
            $response = $this->singlePass->postUser($data_SinglePass);
        }catch(RuntimeException $error){
            return [
                'status'  => false,
                'message' => $error->getMessage()
            ]; 
        }

        return [
            'status'  => true,
            'iD' => $response->user_id,
            'token' => $response->token
        ];
    }

    public function createUser(Request $request, $address_ID, $singlePassID)
    {
        $user_ID = $this->userRepo->create($request, $singlePassID, $address_ID);

        return [
            'status'  => true,
            'iD' => $user_ID->id
        ];
    }

    public function updateUser(Request $request, $address_ID, $singlePassID)
    {
        $user_ID = $this->userRepo->update($request, $singlePassID, $address_ID);

        return [
            'status'  => true,
            'iD' => $user_ID->id
        ];
    }

    public function createAutoRepair(Request $request, $address_ID)
    {
        $auto_repair_ID = $this->autoRepairRepo->create($request, $address_ID);

        return $auto_repair_ID;
    }

    public function link_AutoRepair_User(Request $request, $user_ID, $autoRepair_ID)
    {
        try{
            $this->autoRepairRepo->linkUser($request->role, $user_ID, $autoRepair_ID);

            return [
                'status'  => true,
            ];
        }catch(QueryException $error){
            return [
                'status'  => false,
                'message' => 'Erro ao cadastrar adicionar o usuário à oficina'
            ];
        }
    }
}