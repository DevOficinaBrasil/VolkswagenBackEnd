<?php

namespace App\Http\Controllers;

use App\Services\TrainingService;
use App\Traits\Response;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use Response;

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
        $training = $this->service->getUniqueTraining($id);
        
        return $this->response($training);
    }
    
    /**
     * Display the specified resource with users.
     */
    public function showWithUsers(string $id)
    {
        $users = $this->service->getTrainingWithUsers($id);
        
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
        $validatedData = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'live_url'    => 'sometimes|required|string|max:255',
            'cover'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'active'      => 'sometimes|required|boolean|max:255',
            'certify'     => 'sometimes|required|boolean|max:255',
            'on_live'     => 'sometimes|required|boolean|max:255',
        ]);
        
        $verify = $this->service->verifyBeforeUpdate($validatedData, $id);
        
        if($verify){
            return response()->json($verify, 401);
        }
        
        $data = $this->service->updateTraining($id, $validatedData);

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
