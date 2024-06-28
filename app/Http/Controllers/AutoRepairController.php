<?php

namespace App\Http\Controllers;

use App\Interfaces\AutoRepairRepositoryInterface;
use Illuminate\Http\Request;

class AutoRepairController extends Controller
{
    public function __construct(
        public AutoRepairRepositoryInterface $autoRepairRepo
    ){}

    public function getByCNPJ(Request $request)
    {
        $infos = $this->autoRepairRepo->getInfosAutoRepair($request->cnpj); //14121412141214

        if(!$infos){
            return response()->json(false);
        }
        
        return response()->json($infos);
    }
}