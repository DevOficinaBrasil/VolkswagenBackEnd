<?php

namespace App\Services;

use App\Http\Repository\TrainingRepository;
use App\Models\Concessionaire;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TrainingService
{
    public function __construct(
        protected TrainingRepository $trainingRepo,
    ){}

    public function getTraining(string $id)
    {
        $data = $this->trainingRepo->unique($id);

        return [
            'data'   => $data,
            'status' => 200,
        ];;
    }

    public function getTrainings()
    {
        $data = $this->trainingRepo->all();

        return $data;
    }
    
    public function getAllTrainings()
    {
        $data = $this->trainingRepo->last();
        
        if($data->isNotEmpty()){
            return [
                'data'   => $data,
                'status' => 200,
            ];
        }

        return [
            'data'   => 'Nenhum treinamento encontrado',
            'status' => 404
        ];
    }

    public function getTrainingUsers(string $id)
    {
        $data = $this->trainingRepo->usersSubscribed($id);

        return $data;
    }

    public function getAllTrainingsById($id)
    { 
        try{
            $data = $this->trainingRepo->find($id);
            
            if(empty($data)){
                throw new Exception("Nenhum treinamento encontrado");
            }
        }catch(\Exception $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 404
            ];
        }
        
        return [
            'data'   => $data,
            'status' => 200
        ];
    }

    public function saveTrainingUser(Request $request)
    {
        try{
            $request->validate([
                'concessionaireId' => 'required|string',
                'trainingId'       => 'required|string',
                'userId'           => 'required|string',
            ]);

            $this->trainingRepo->create($request->trainingId, $request->userId, $request->concessionaireId);
        }catch(QueryException $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 400,
            ];
        }catch(\Exception $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 400,
            ];
        }

        return [
            'data'   => "Cadastro realizado com sucesso!",
            'status' => 201,
        ];
    }

    public function updateTraining(int $id, Request $request)
    {
        try{
            $this->trainingRepo->update($id, $request);
        }catch(\Exception $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 400
            ];
        }

        return [
            'data'   => "Inscrição atualizada!",
            'status' => 201,
        ];
    }
}