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

    public function create(Request $request, $concessionaire = false)
    {
        try{
            $data = $this->model->create([
                'common_user_id'        => $request->user,
                'trainings_id'          => $request->training,
                'Avalie'                => $request->rating,
                'DuvidasChatPresencial' => $request->quest2,
                'ExperienciaRetorno'    => $request->quest1,
                'HorarioPrefere'        => $request->quest3,
                'QuaisTemas'            => $request->suggestion,
            ]);

            if($concessionaire){
                $presence = new ConcessionaireAreaRepository();

                $presence->updatePresence($request->training, $request->user, $concessionaire);
            }

            return $data;
        }catch(QueryException $error){
            return $error->getMessage();
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