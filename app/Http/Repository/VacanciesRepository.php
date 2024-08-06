<?php

namespace App\Http\Repository;

use App\Interfaces\Repository;
use App\Models\Vacancies;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VacanciesRepository implements Repository
{
    public function __construct(
        protected Vacancies $model
    ){}
    
    public function getAll()
    {
        $data = $this->model->with('concessionaire')->get();

        return $data;
    }
    
    public function findUnique(int $id)
    {
        $data = $this->model->where('training_id', $id)->with('concessionaire')->get();

        return $data;
    }

    public function update(int $id, $data)
    {
        $resource = $this->model->findOrFail($id);
        
        $resource->update($data);

        return $resource;
    }

    public function getConcessionaireTrainings(int $id)
    {
        $data = $this->model->where('concessionaire_id', $id)->with('trainings')->get();

        return $data;
    }
    
    public function insertAConcessionaire(int $concessionaireId, int $trainingId)
    {
        try {
            $this->model->where('concessionaire_id', $concessionaireId)->where('training_id', $trainingId)->firstOrFail();

            return throw new Exception('Concessionária já cadastrada');
        } catch (ModelNotFoundException $e) {
            $data = $this->model->create([
                'concessionaire_id' => $concessionaireId,
                'training_id' => $trainingId,
                'vacancies' => 0
            ]);
        }

        return $data;
    }
}