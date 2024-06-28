<?php

namespace App\Http\Repository;

use App\Models\State;
use App\Interfaces\AddressRepositoryInterface;

class StateRepository implements AddressRepositoryInterface
{
    public function __construct(
        protected State $model
    ){}

    public function search($attr, $term)
    {
        $response = $this->model->where($attr, $term)->first();

        return $response;
    }

    public function create($value, $foreign_key = null)
    {
        $record = $this->model->create([
            'value' => $value
        ]);

        return $record->id;
    }

    public function existOrCreate($value, ?int $foreign_key = null)
    {
        $record = $this->model->firstOrCreate([
            'value' => $value
        ]);

        return $record->id;
    }
}