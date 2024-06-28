<?php

namespace App\Http\Controllers;

use App\Models\LegacyUser;
use Illuminate\Http\Request;

class UserLegacyController extends Controller
{
    public function search(Request $request)
    {
        $data = LegacyUser::where('CPF', $request->cpf)->get();
        
        if($data->isNotEmpty()){
            return response()->json($data->first());
        }

        return response()->json(false, 404);
    }
}
