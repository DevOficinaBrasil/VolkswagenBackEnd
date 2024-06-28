<?php

namespace App\Http\Controllers;

use App\Services\TrainingService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected TrainingService $service,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $training = $this->service->getTrainings();

        return response()->json($training);
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
        $users = $this->service->getTrainingUsers($id);
        
        return response()->json($users);
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
