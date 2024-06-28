<?php
namespace App\Http\Repository;

use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingRepository
{
    public function __construct(
        protected Training $model,
        protected TrainingUser $modelFK
    ){}

    public function unique(string $id)
    {
        $data = $this->model->find($id);

        return $data;  
    }

    public function all()
    {
        $data = $this->model->all();

        return $data;
    }

    public function especifyConcessionaire($id)
    {
        $data = $this->model->with(['concessionaire' => function ($query) use ($id) {
            $query->where('concessionaire_id', $id);
        }])
        ->orderByDesc('active')
        ->get();

        return $data;
    }

    public function last()
    {
        $data = $this->model->orderByDesc('id')
            ->with('concessionaires.address.city.state')
            ->take(4)
            ->get();
        
        return $data;
    }

    public function usersSubscribed($id)
    {
        $data = $this->model->where('id', $id)
            ->with('users')
            ->get()
            ->first();
        
        return $data;
    }

    public function find($id)
    {   
        try{
            $data = DB::select('
                SELECT 
                    trainings.cover, 
                    trainings.name, 
                    trainings.date, 
                    trainings.id, 
                    trainings.active, 
                    concessionaire_training_user.id AS pivot_id, 
                    concessionaire.id AS concessionaire_id, 
                    concessionaire.fantasy_name,
                    address.street, 
                    address.number,
                    city.value AS city, 
                    state.value AS state
                FROM trainings
                LEFT JOIN concessionaire_training_user 
                    ON trainings.id = concessionaire_training_user.trainings_id
                LEFT JOIN common_user 
                    ON common_user.id = concessionaire_training_user.common_user_id
                LEFT JOIN concessionaire 
                    ON concessionaire.id = concessionaire_training_user.concessionaire_id
                LEFT JOIN address 
                    ON concessionaire.concessionaire_address = address.id
                LEFT JOIN city 
                    ON address.city_id = city.id
                LEFT JOIN state 
                    ON city.state_id = state.id
                WHERE common_user.id = ?'
            , [$id]);
        }catch(ModelNotFoundException){
            throw new Exception("Nenhum usuário encontrado");
        }
        
        return $data;
    }

    public function create(int $trainingID, int $userID, int $concessionaireID = 0)
    {
        $exist = $this->modelFK->where('trainings_id', $trainingID)
            ->where('common_user_id', $userID)
            ->exists();

        if($exist){
            throw new Exception("Você já se cadastrou nesse treinamento");
        }
        
        $record = $this->modelFK->create([
            'common_user_id' => $userID,
            'concessionaire_id' => $concessionaireID,
            'trainings_id' => $trainingID,
            'presence' => 0,
        ]);
        
        return $record;
    }

    public function update(int $id, Request $request)
    {
        $record = $this->modelFK->findOrFail($id);
        
        $record->update(['concessionaire_id' => $request->concessionaireId]);

        return $record;
    }
}