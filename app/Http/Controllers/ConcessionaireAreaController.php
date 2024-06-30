<?php

namespace App\Http\Controllers;

use App\Services\ConcessionaireAreaService;
use Illuminate\Http\Request;

class ConcessionaireAreaController extends Controller
{
    public function __construct(
        protected ConcessionaireAreaService $service,
    ){}

    public function getTrainings(Request $request)
    {
        $response = $this->service->trainings($request->concessionaireId);

        return response()->json($response);
    }

    public function getUserOnTraining(Request $request)
    {
        $parameters = $request->all();

        $response = $this->service->users($parameters['trainingId'], $parameters['concessionaireId']);

        return response()->json($response);
    }

    public function updatePresence(Request $request)
    {
        $parameters = $request->all();

        $response = $this->service->updatePresence($parameters['trainingId'], $parameters['userId'], $parameters['concessionaireId']);

        return response()->json($response['message'], $response['status']);
    }
}
