<?php

namespace App\Http\Controllers;

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
    public function getTrainingPresence(Request $request)
    {

        $usuarioID = $request->input('userID');

        try {

            $result = DB::table('trainings as a')
                ->selectRaw('
                a.*, 
                MAX(CASE WHEN b.trainings_id IS NOT NULL THEN 1 ELSE 0 END) AS PreencheuFicha,
                MAX(CASE WHEN c.trainings_id IS NOT NULL THEN 1 ELSE 0 END) AS Inscrito,
                MAX(CASE WHEN d.TreinamentoParticipou IS NOT NULL THEN 1 ELSE 0 END) AS TreinamentoParticipou
            ')
                ->leftJoin('general_sheet as b', function ($join) use ($usuarioID) {
                    $join->on('b.trainings_id', '=', 'a.id')
                        ->where('b.common_user_id', '=', $usuarioID);
                })
                ->leftJoin('concessionaire_training_user as c', function ($join) use ($usuarioID) {
                    $join->on('c.trainings_id', '=', 'a.id')
                        ->where('c.common_user_id', '=', $usuarioID);
                })
                ->leftJoin('common_user_log as d', function ($join) {
                    $join->on('d.Treinamento', '=', 'c.trainings_id');
                })
                ->groupBy('a.id')
                ->get();

            return response()->json($result);

            return response()->json([
                'data'   => $result,
                'status' => 200
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function putTrainingPresence(Request $request)
    {

        $usuarioID = $request->input('userID');


        try {


            $existingUser = TrainingUser::where('trainingID', $request->input('trainingID'))
                ->where('usuarioID', $usuarioID)
                ->first();

            // Se não existe, cria um novo registro
            if (!$existingUser) {
                TrainingUser::create([
                    'trainingID' => $request->input('trainingID'),
                    'usuarioID' => $usuarioID,
                    'campo_extra' => 0, // Substitua 'campo_extra' pelo campo que deseja definir como 0
                ]);
            }

            // Se não existe, cria um novo registro
            CommonUserLog::create([
                "CadastroID" => $request->input('userID'),
                "Treinamento" => $request->input('trainingID'),
                "TreinamentoParticipou" => "S",
                "Participou" => "O",
            ]);


            return response()->json([
                'data'   => 'Presença realizada',
                'status' => 200
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function setTraininOnLive(Request $request)
    {
        // $usuarioID = $request->input('userID');
        // $data = [];

        try {


            // Se não existe, cria um novo registro
            Training::where('id', $request->input('trainingId'))->update([
                "on_live" => $request->input('onLive'),

            ]);


            return response()->json([
                'data'   => 'Mudança feita da live...',
                'status' => 200
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
