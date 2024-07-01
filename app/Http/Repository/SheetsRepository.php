<?php

namespace App\Http\Repository;

use App\Models\Sheets;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SheetsRepository
{
    public function __construct(
        protected Sheets $model
    ){}

    public function create(Request $request, $answers)
    {
        DB::beginTransaction();

        try{
            $data = $this->model->create([
                'common_user_id' => $request->user,
                'trainings_id'   => $request->training,
                'answers'        => $answers,
                'format'         => $request->user,
            ]);

            DB::commit();

            return true;
        }catch(QueryException $error){
            DB::rollBack();

            return false;
        }
    }

    public function find(string $user, string $training)
    {
        $data = $this->model->where('common_user_id', $user)->where('trainings_id', $training)->get()->first();

        if($data){
            return true;
        }else{
            return false;
        }
    }
}