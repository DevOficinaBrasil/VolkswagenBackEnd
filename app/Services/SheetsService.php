<?php

namespace App\Services;

use App\Http\Repository\SheetsRepository;
use App\Models\TrainingUser;
use Illuminate\Http\Request;

class SheetsService
{
    public function __construct(
        protected SheetsRepository $sheetsRepository,
    ){}
    
    public function save(Request $request)
    {
        if($request->present){
            $concessionaire = TrainingUser::where('common_user_id', $request->user)->where('trainings_id', $request->training)->get()->first();
            
            $data = $this->sheetsRepository->create($request, $concessionaire->concessionaire_id);
        }else{
            $data = $this->sheetsRepository->create($request);
        }

        return $data;
    }

    public function find(Request $request)
    {
        $params = $request->all();

        $data = $this->sheetsRepository->find($params['user'], $params['training']);

        if($data){
            return true;
        }

        return false;
    }
}