<?php

namespace App\Http\Repository;

use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;
// use App\Services\AddressService;

class AddressRepository implements AddressRepositoryInterface
{
    public function __construct(
        protected Address $model,
        // protected AddressService $addressService
    ){}

    public function search($attr, $term)
    {
        $response = $this->model->where($attr, $term)->first();

        return $response;
    }

    public function getAddress($id)
    {
        $data = $this->model->where('id', $id)->get()->first();

        return $data;
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

    
    public function update($value, $foreign_key = null)
    {
       

        $record = $this->model->where('id', $value->id)->update([
            'cep'        => $value['cep'],
            'street'     => $value['street'],
            'number'     => $value['number'],
            'complement' => $value['complement'],
            'city_id'    => $value['city_id']
        ]);


        return $record;
    }

    public function existOrCreate($value, ?int $foreign_key = null)
    {
        //
    }
}