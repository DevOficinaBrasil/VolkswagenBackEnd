<?php

namespace App\Http\Repository;

use App\Interfaces\Repository;
use App\Models\Vacancies;

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
}