<?php

namespace App\Http\Controllers;

use App\Services\ConcessionaireService;
use Illuminate\Http\Request;

class ConcessionaireControler extends Controller
{
    public function __construct(
        protected ConcessionaireService $service
    ){}
    
    public function getByAddress(Request $request){
        try{
            $data = $this->service->getConcessionaireByAddress($request);

            return response()->json($data, 200);
        }catch(\RuntimeException $error){
            return response()->json($error->getMessage(), 400);
        }
    }
}
//oficina@2024