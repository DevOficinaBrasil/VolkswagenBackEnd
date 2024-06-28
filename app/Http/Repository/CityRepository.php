<?php

namespace App\Http\Repository;

use App\Models\City;
use App\Interfaces\AddressRepositoryInterface;

class CityRepository implements AddressRepositoryInterface
{
    public function __construct(
        protected City $model
    ){}

    public function search($attr, $term)
    {
        $response = $this->model->where($attr, $term)->first();

        return $response;
    }

    public function create($value, $foreign_key = null)
    {
        $record = $this->model->create([
            'value'    => $value,
            'state_id' => $foreign_key
        ]);

        return $record->id;
    }

    public function existOrCreate($value, ?int $foreign_key = null)
    {
        $record = $this->model->firstOrCreate(
            ['value'    => $value],
            ['state_id' => $foreign_key]
        );

        return $record->id;
    }
}