<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Services\ConcessionaireService;
use App\Models\Concessionaire;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use App\Services\TrainingService;
use App\Services\UserService;
use App\Traits\GenerateParamsNotification;
use App\Traits\Response;

class TrainingController extends Controller
{
    use Response, GenerateParamsNotification;

    public function __construct(
        protected TrainingService $service,
        protected UserService $userService,
        protected ConcessionaireService $concessionaireService,
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

        /*$concessionaire = $this->concessionaireService->find($request->concessionaireId);

        if($data['status'] == 201){
            $params = $this->trainingCreate($request->trainingId, $concessionaire);

            SendNotificationEvent::dispatch($this->userService->allInfos($request->userId)->email, $params);
        }*/

        return $this->response($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $training = $this->service->getTraining($id);

        return $this->response($training);
    }
    public function getTrainingByConcessionaireId(Request $request)
    {
        $body = $request->all();
        error_log($request->concessionaireID);
        $data = Concessionaire::where('id', $request->concessionaireID)
          ->with('trainingVacancies')
          ->get();
    
    
    
        // return response()->json($data, 200);
    
        if ($data->isNotEmpty()) {
          foreach ($data as $unique) {
            

            foreach ($unique->trainingVacancies as $training) {
                $count = TrainingUser::where('concessionaire_id', $request->concessionaireID)
              ->where('trainings_id', $training->id)->count();

              $training['vacanciesLeft'] =  $training->pivot->vacancies - $count;
            } 
              
            // return response()->json([
            //   'data'   => $unique->trainingVacancies,
            //   'status' => 200,
            // ]);
    
            // $vacancies = $unique->trainingVacancies[0]->pivot->vacancies - $count;
    
            // $unique->vacancies = $vacancies;
          }
          return response()->json([
            'data'   => $data,
            'status' => 200,
          ]);
        }
    
    
    
        return response()->json([
          'data'   => 'Nenhuma concessionaria encontrado',
          'status' => 404
        ]);
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
