<?php

namespace App\Http\Repository;

use App\Interfaces\AutoRepairRepositoryInterface;
use App\Models\AutoRepair;
use App\Models\AutoRepairUSer;

class AutoRepairRepository implements AutoRepairRepositoryInterface
{
    public function __construct(
        protected AutoRepair $model,
        protected AutoRepairUSer $modelLink
    ){}

    public function getInfosAutoRepair($cnpj)
    {
        $data = $this->model->where('cnpj', $cnpj)->get();

        if($data->isEmpty()){
            return false;
        }

        return $data->first();
    }

    public function getInfosAutoRepairByID($id)
    {
        $dataLink = $this->modelLink->where('common_user', $id)->get();
        if($dataLink->isEmpty()){
            return false;
        }
        $data = $this->model->where('id', $dataLink->auto_repair_id)->get();

        if($data->isEmpty()){
            return false;
        }

        return $data->first();
    }

    public function create($auto_repair_DATA, $address_ID)
    {
        $record = $this->model->create([
            'CNPJ'                => $auto_repair_DATA->cnpj,
            'fantasy_name'        => $auto_repair_DATA->fantasy_name,
            'branch_activity'     => $auto_repair_DATA->branch_activity,
            'auto_repair_phone'   => $auto_repair_DATA->auto_repair_phone,
            'auto_repair_address' => $address_ID,
        ]);
        
        return $record->id;
    }

    public function linkUser($role, $user_ID, $autoRepair_ID)
    {
        $record = $this->modelLink->create([
            'common_user' => $user_ID,
            'auto_repair_id' => $autoRepair_ID,
            'role' => $role,
        ]);
        
        return $record->id;
    }
}