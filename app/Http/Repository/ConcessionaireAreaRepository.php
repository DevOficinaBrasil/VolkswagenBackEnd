<?php
namespace App\Http\Repository;

use App\Events\LogUser;
use App\Models\Concessionaire;
use App\Models\TrainingUser;
use App\Models\User;
use Illuminate\Database\QueryException;

class ConcessionaireAreaRepository
{
    public function getAllUsersOnTrainingByConcessionaire(string $training, string $concessionaire)
    {
        $data = User::whereHas('trainings', function ($query) use ($training, $concessionaire) {
            $query->where('trainings_id', $training)
                  ->where('concessionaire_id', $concessionaire);
        })
        ->with(['trainings' => function ($query) use ($training, $concessionaire) {
            $query->where('trainings_id', $training)
                  ->where('concessionaire_id', $concessionaire)
                  ->withPivot('presence');
        }])
        ->get();

        return $data;
    }

    public function updatePresence(string $training, string $user, string $concessionaire)
    {
        $save = TrainingUser::where('trainings_id', $training)->where('common_user_id', $user)->where('concessionaire_id', $concessionaire)->get()->first();

        $save->presence = !$save->presence;

        LogUser::dispatch($training, $user, $concessionaire);

        try{
            $save->save();
        }catch(QueryException){
            return false;
        }
        
        return true;
    }
}