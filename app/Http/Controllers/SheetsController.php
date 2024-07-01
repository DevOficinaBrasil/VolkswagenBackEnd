<?php

namespace App\Http\Controllers;

use App\Services\SheetsService;
use Illuminate\Http\Request;

class SheetsController extends Controller
{
    public function __construct(
        protected SheetsService $service
    ){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->service->save($request);

        if($data->exists){
            return response()->json('Respostas salvas!', 200);
        }

        return response()->json('erro ao salvar as resposta', 400);
    }

    /**
     * Display the specified resource.
     */
    public function verify(Request $request)
    {
        $data = $this->service->find($request);

        if($data){
            return response()->json(true, 200);
        }

        return response()->json(false, 404);
    }
}
