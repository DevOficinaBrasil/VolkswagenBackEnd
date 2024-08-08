<?php
namespace App\Http\Repository;

use App\Models\Concessionaire;

class ConcessionaireRepository
{
    public function __construct(
        protected Concessionaire $model,
    ){}

    public function all()
    {
        $data = $this->model->all();

        return $data;
    }

    public function getInfos($id)
    {
        $data = $this->model->find($id); // Retornar endereço
        
        return $data;
    }

    public function getBySinglePassId($id)
    {
        $data = $this->model->where('concessionaire_login_id', $id)->get()->first();

        return $data;
    }

    public function getByAddress($state, $city, $id)
    {
        $data = $this->model->whereHas('trainingVacancies', function ($query) use ($id) {
            $query->where('training_id', $id);
        })
        ->whereHas('address.city', function ($query) use ($state, $city) {
            $query->where('value', $city)
            ->whereHas('state', function ($query) use ($state) {
                $query->where('value', $state);
            });
        })
        ->with('trainingVacancies', function ($query) use ($id) {
            $query->where('training_id', $id);
        })
        ->with('address.city.state')
        ->get();
        
        return $data;
    }

    public function store($params)
    {
        $data = $this->model->create($params);

        return $data;
    }
}