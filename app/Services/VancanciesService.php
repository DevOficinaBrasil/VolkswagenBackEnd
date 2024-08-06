<?php

namespace App\Services;

use App\Http\Repository\VacanciesRepository;
use Illuminate\Database\QueryException;

class VancanciesService
{
    public function __construct(
        protected VacanciesRepository $repository,
    ){}

    public function all()
    {
        $return = $this->repository->getAll();

        return $return;
    }

    public function find(int $id)
    {
        $return = $this->repository->findUnique($id);

        return $return;
    }

    public function updateUnique(int $id, array $data)
    {
        try{
            $this->repository->update($id, $data);
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

    public function addNewConcessionaireOnTraining(string $concessionaireId, string $trainingId)
    {
        try{
            $this->repository->insertAConcessionaire(intval($concessionaireId), intval($trainingId));
        }catch(QueryException $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 400
            ];
        }catch(\Exception $error){
            return [
                'data'   => $error->getMessage(),
                'status' => 401
            ];
        }

        return [
            'data'   => "Concessionária adicionada com sucesso",
            'status' => 201,
        ];
    }
}