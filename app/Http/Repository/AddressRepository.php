<?php

namespace App\Http\Repository;

use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;

class AddressRepository implements AddressRepositoryInterface
{
    public function __construct(
        protected Address $model
    ){}

    public function search($attr, $term)
    {
        $response = $this->model->where($attr, $term)->first();

        return $response;
    }

    public function create($value, $foreign_key = null)
    {
        $record = $this->model->create([
            'cep'        => $value['cep'],
            'street'     => $value['street'],
            'number'     => $value['number'],
            'complement' => $value['complement'],
            'city_id'    => $foreign_key
        ]);

        return $record->id;
    }

    public function existOrCreate($value, ?int $foreign_key = null)
    {
        //
    }
}