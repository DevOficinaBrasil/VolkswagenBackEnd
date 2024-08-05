<?php

namespace App\Http\Controllers;

use App\Events\LogUser;
use App\Events\SendNotificationEvent;
use App\Models\CommonUserLog;
use App\Services\ConcessionaireService;
use App\Models\Concessionaire;
use App\Models\Training;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use App\Services\TrainingService;
use App\Services\UserService;
use App\Traits\GenerateParamsNotification;
use App\Traits\Response;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    use Response, GenerateParamsNotification;

    public function __construct(
        protected TrainingService $service,
        protected UserService $userService,
        protected ConcessionaireService $concessionaireService,
    ) {
    }

    /**
     * 
     * Display a listing of the resource.
     * 
     */
    public function index()
    {
        $training = $this->service->getAllTrainings();

        return $this->response($training);
    }

    /**
     * 
     * Store a newly created resource in storage.
     * 
     */
    public function store(Request $request)
    {
        $data = $this->service->saveTrainingUser($request->trainingId, $request->userId, $request->concessionaireId);

        /*$concessionaire = $this->concessionaireService->find($request->concessionaireId);

        if($data['status'] == 201){
            $params = $this->trainingCreate($request->trainingId, $concessionaire);

            SendNotificationEvent::dispatch($this->userService->allInfos($request->userId)->email, $params);
        }*/

        return $this->response($data);
    }

    /**
     * 
     * Display the specified resource.
     * 
     */
    public function show(string $id)
    {
        $training = $this->service->getUniqueTraining($id);

        return $this->response($training);
    }

    /**
     * 
     * Display a listing of subscribed trainings
     * 
     */
    public function exib(string $id)
    {
        $userId = $this->userService->allInfos($id);

        $data = $this->service->getAllTrainingsByUserId($userId->id);

        return $this->response($data);
    }

    public function putTrainingPresence(Request $request)
    {
        $data = $this->service->saveTrainingUser($request->trainingId, $request->userId);
        
        $this->service->updateTrainingFK($data['info']->id, 'presence', 1);

        LogUser::dispatch($request->trainingId, $request->userId, 0, 'O');
        
        return response()->json('Presença atualizada');
    }

    public function releaseSheet(Request $request)
    {

        // $usuarioID = $request->input('userID');
        // $data = [];

        try {

            // Se não existe, cria um novo registro
            Training::where('id', $request->input('trainingId'))->update([
                "certify" => $request->input('certify'),

            ]);

            return response()->json([
                'data'   => 'Ficha liberada...',
                'status' => 200
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified register in FK table.
     */
    public function updateConcessionaire(Request $request, string $id)
    {
        $data = $this->service->updateTrainingFK($id, 'concessionaire_id', $request->concessionaireId);

        return $this->response($data);
    }

    public function active()
    {
        $trainings = $this->service->getTrainings();
        
        foreach($trainings as $training){
            if($training->active == 1){
                return response()->json($training);
            }
        }

        return response()->json('Nenhum treinamento ativo', 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
