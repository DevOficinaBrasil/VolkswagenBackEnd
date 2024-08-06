<?php

namespace App\Services;

use App\Http\Repository\TrainingRepository;
use App\Models\Concessionaire;
use App\Models\TrainingUser;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingService
{
    public function __construct(
        protected TrainingRepository $trainingRepo,
    ){}
    
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

    public function getUniqueTraining(string $id)
    {
        $data = $this->trainingRepo->unique($id);
        
        if($data === null){
            return [
                'data'   => 'Dados não encontrados',
                'status' => 404,
            ];
        }

        return [
            'data'   => $data,
            'status' => 200,
        ];
    }

    public function getTrainings()
    {
        $data = $this->trainingRepo->all();

        return $data;
    }

    public function getAllTrainingsByUserId($id)
    { 
        $data = $this->trainingRepo->find($id);

        try{
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

    public function getTrainingWithUsers(string $id)
    {
        $data = $this->trainingRepo->usersSubscribed($id);

        return $data;
    }

    public function saveTrainingUser(int $trainingId, int $userId, int $concessionaireId = 0)
    {
        $validator = Validator::make([
            'trainingId' => $trainingId,
            'userId'     => $userId,
        ],[
            'trainingId'       => 'required|integer',
            'userId'           => 'required|integer',
        ]);

        if($validator->fails()){
            return [
                'data'   => $validator->errors(),
                'status' => 400,
            ];
        }

        try{
            $record = $this->trainingRepo->createFK($trainingId, $userId, $concessionaireId);
        }catch(QueryException){
            return [
                'data'   => 'Erro ao cadastrar o usuário',
                'status' => 400,
            ];
        }

        return [
            'info'   => $record,
            'data'   => 'Cadastro realizado com sucesso',
            'status' => 201,
        ];
    }

    public function updateTrainingFK(int $id, string $param, string $argument)
    {
        try{
            $this->trainingRepo->updateFK($id, $param, $argument);
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

    public function updateTraining(int $id, array $data)
    {
        try{
            $this->trainingRepo->update($id, $data);
        }catch(\Exception $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 400
            ];
        }

        return [
            'data'   => "Dados atualizados",
            'status' => 201,
        ];
    }

    public function verifyBeforeUpdate(array $data, string $id)
    {
        if($data['active']){
            $trainings = $this->getTrainings();

            foreach($trainings as $training){
                if($training->active == 1 && $training->id != $id){
                    return 'Já existe um treinamento ativo';
                }
            }
        }

        return false;
    }
}