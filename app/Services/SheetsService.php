<?php

namespace App\Services;

use App\Http\Repository\SheetsRepository;
use Illuminate\Http\Request;

class SheetsService
{
    public function __construct(
        protected SheetsRepository $sheetsRepository,
    ){}
    
    public function save(Request $request)
    {
        $data = $this->sheetsRepository->create($request);

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