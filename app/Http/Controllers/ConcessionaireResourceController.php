<?php

namespace App\Http\Controllers;

use App\Services\AccessService;
use App\Services\ConcessionaireService;
use Illuminate\Http\Request;

class ConcessionaireResourceController extends Controller
{
    public function __construct(
        protected ConcessionaireService $service,
        protected AccessService $accessService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->service->getAll();

        return $data;
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
        try{
            $request->validate([
                'cnpj'       => 'required|string|unique:App\Models\Concessionaire,CNPJ',
                'fantasy'    => 'required|string',
                'manager'    => 'required|string',
                'certify'    => 'required|string',
                'phone'      => 'required|string',
                'dn'         => 'required|string',
                'cep'        => 'required|string',
                'state'      => 'required|string',
                'city'       => 'required|string',
                'street'     => 'required|string',
                'complement' => 'required|string',
                'number'     => 'required|string',
                'email'      => 'required|email|unique:App\Models\Concessionaire,email',
            ]);
        }catch(\Exception $error){
            return response()->json($error->getMessage(), 400);
        }

        $singlePass = $this->service->generatePassword($request->fantasy, $request->email, $request->dn);

        $address = $this->accessService->createAddress($request);

        try{
            $newConcessionaire = $this->service->addNewConcessionaire($singlePass, $address, $request);
        }catch(\Exception $error){
            return response()->json($error->getMessage(), 400);
        }

        return response()->json('Concessionária adicionada');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $data = $this->service->generatePassword($id);

        return response()->json($data['message'], $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
