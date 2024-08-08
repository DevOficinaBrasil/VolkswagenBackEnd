<?php

namespace App\Http\Controllers;

use App\Http\Repository\AddressRepository;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AddressService;
use App\Services\AutoRepairService;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected AddressService $addressService,
        protected AutoRepairService $autoRepairService,
        protected AddressRepository $addressRepo
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->service->getAll();

        return response()->json($users, 200);
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

        if ($userData) {
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
            'user' => $user,
            'adressUser' => $addressData,
            'autoRepar' => $autoRepair
        ];
        if ($userData) {
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

        if ($data) {
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

        if ($data) {
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

    public function createUserConecta(Request $request)
    {
        $user = $request->only(
            "name",
            "email",
            "phone",
            "born_at",
            "document",
            "password",
            "cnpj",
            "fantasy_name",
            "auto_repair_phone",
            "auto_repair_cep",
            "branch_activity",
            "auto_repair_city",
            "auto_repair_state",
            "auto_repair_street",
            "auto_repair_number"
        );

        try {
            // Hashear a senha

            // Montar a consulta SQL usando DB::raw
            $insertQuery = "
                INSERT INTO conecta_cadastros (name, email, phone, born_at, document, password, cnpj, fantasy_name, auto_repair_phone, auto_repair_cep, branch_activity, auto_repair_city, auto_repair_state, auto_repair_street, auto_repair_number)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";

            // Executar a consulta
            DB::insert(DB::raw($insertQuery), [
                $user['name'],
                $user['email'],
                $user['phone'],
                $user['born_at'],
                $user['document'],
                $user['password'],
                $user['cnpj'],
                $user['fantasy_name'],
                $user['auto_repair_phone'],
                $user['auto_repair_cep'],
                $user['branch_activity'],
                $user['auto_repair_city'],
                $user['auto_repair_state'],
                $user['auto_repair_street'],
                $user['auto_repair_number'],
            ]);

            // Retornar uma resposta de sucesso
            return response()->json(['message' => 'User created successfully'], 201);
        } catch (\Exception $e) {
            // Retornar uma resposta de erro
            return response()->json(['error' => 'User creation failed', 'message' => $e->getMessage()], 500);
        }
    }
}
