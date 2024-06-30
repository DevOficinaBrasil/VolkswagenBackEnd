<?php

namespace App\Services;
use App\Interfaces\AutoRepairRepositoryInterface;

class AutoRepairService
{
    public function __construct(
        protected AutoRepairRepositoryInterface $AutoRepa,
    ){}


    public function getInfosAutoRepairByID($id) {
        $data = $this->AutoRepa->getInfosAutoRepairByID($id);

        return $data;
    }


}