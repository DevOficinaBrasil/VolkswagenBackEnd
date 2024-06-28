<?php

namespace App\Http\Controllers;

use App\Services\ConcessionaireService;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function __construct(
        protected ConcessionaireService $service
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        $concessionaireData = $this->service->findBySinglePassId($id);
        
        if($concessionaireData){
            return response()->json($concessionaireData, 200);
        }

        return response()->json('usuário não encontrado', 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
