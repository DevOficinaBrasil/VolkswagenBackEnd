<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Concessionaire;
use App\Services\AddressService;
use App\Services\ConcessionaireService;
use Illuminate\Http\Request;

class ConcessionaireControler extends Controller
{
    public function __construct(
        protected ConcessionaireService $service,
        protected AddressService $addressService,
    ){}
    
    public function getByAddress(Request $request){
        try{
            $data = $this->service->getConcessionaireByAddress($request);

            return response()->json($data, 200);
        }catch(\RuntimeException $error){
            return response()->json($error->getMessage(), 400);
        }
    }
    public function getConcessionaireOnlyByAddress(Request $request){

        error_log($request->city);

        $state_ID = $this->addressService->ifExistState($request->state);

        $city_id = $this->addressService->ifExistCity($request->city, $state_ID);

        // $request->validate([
        //     'city_id' => 'required|integer|exists:city,id'
        // ]);

        // Obter o ID da cidade da requisição
        // $city_id = $request->input('city_id');

        // Encontrar a cidade
        $city = City::findOrFail($city_id);

        // Obter as concessionárias através do relacionamento
        $concessionaires = Concessionaire::whereHas('address', function ($query) use ($city_id) {
            $query->where('city_id', $city_id);
        })->with('address')->get();

        // Retornar as concessionárias encontradas
        // error_log($concessionaires);
        if(!$concessionaires){

            return response()->json("Sem concessionarias nesta região", 404);
        }
        return response()->json($concessionaires);
    }
}
//oficina@2024