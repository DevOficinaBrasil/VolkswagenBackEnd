<?php

namespace App\Http\Controllers;

use App\Models\LegacyUser;
use App\Services\AccessService;
use Illuminate\Http\Request;

class UserLegacyController extends Controller
{
    public function __construct(
        protected AccessService $accessService
    ){}
    
    public function search(Request $request)
    {
        $data = LegacyUser::where('CPF', $request->cpf)->get();
        
        $verify = $this->accessService->verifySituation($request);

        if($verify == 2){
            return response()->json(true, 401);
        }

        if($data->isNotEmpty()){
            return response()->json($data->first());
        }

        return response()->json(false, 404);
    }
}
