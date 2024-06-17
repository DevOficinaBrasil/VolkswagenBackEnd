<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrainingService;
use App\Services\UserService;
use App\Traits\Response;

class TrainingController extends Controller
{
    use Response;

    public function __construct(
        protected TrainingService $service,
        protected UserService $userService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $training = $this->service->getAllTrainings();

        return $this->response($training);
    }

    /**
     * Display a listing of subscribed trainings
     */
    public function exib(string $id)
    {
        $userId = $this->userService->allInfos($id);
        
        $data = $this->service->getAllTrainingsById($userId->id);
        
        return $this->response($data);
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
        $data = $this->service->saveTrainingUser($request);

        return $this->response($data);
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
        $data = $this->service->updateTraining($id, $request);

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
