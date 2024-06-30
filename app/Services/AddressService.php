<?php

namespace App\Services;

class AddressService
{
    public function __construct(
        protected $StateRepository,
        protected $CityRepository,
        protected $AddressRepository,
    ){}

    public function ifExistState($state)
    {
        $record = $this->StateRepository->existOrCreate($state);

        return $record;
    }

    public function getAddress($id)
    {
        $address = $this->AddressRepository->getAddress($id);

        return $address;
    }

    public function ifExistCity($city, $state_ID)
    {
        $record = $this->CityRepository->existOrCreate($city, $state_ID);

        return $record;
    }

    public function addAddress($address, $city_ID)
    {
        $record_id = $this->AddressRepository->create($address, $city_ID);

        return $record_id;
    }
}