<?php

namespace App\Http\Controllers;

use App\Services\VancanciesService;
use App\Traits\Response;
use Illuminate\Http\Request;

class VacanciesController extends Controller
{
    use Response;

    public function __construct(
        protected VancanciesService $service
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
        $data = $this->service->find($id);

        return response()->json($data);
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
        // Adicionar o created_at e updated_at no banco de produção
        $validatedData = $request->validate([
            'vacancies'    => 'sometimes|required|string|max:255',
        ]);

        $data = $this->service->updateUnique($id, $validatedData);

        return $this->response($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
