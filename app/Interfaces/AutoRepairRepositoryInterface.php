<?php

namespace App\Interfaces;

interface AutoRepairRepositoryInterface
{
    public function getInfosAutoRepair($cnpj);

    public function create($auto_repair_DATA, $address_ID);

    public function getInfosAutoRepairByID($id);

    public function linkUser($role, $user_ID, $autoRepair_ID);
}