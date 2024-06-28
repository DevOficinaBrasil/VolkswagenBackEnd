<?php

namespace App\Services;

use App\Http\Repository\ConcessionaireRepository;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use RuntimeException;

class ConcessionaireService
{
    public function __construct(
        protected ConcessionaireRepository $concessionaireRepo,
        protected TrainingUser $vacanciesCount
    ){}

    public function getAll()
    {
        return $this->concessionaireRepo->all();
    }

    public function find(int $id)
    {
        return $this->concessionaireRepo->getInfos($id);
    }

    public function findBySinglePassId(int $id)
    {
        return $this->concessionaireRepo->getBySinglePassId($id);
    }
    
    public function getConcessionaireByAddress(Request $request)
    {
        if($request->has('state') && $request->has('city')){
            $data = $this->concessionaireRepo->getByAddress($request->query('state'), $request->query('city'), $request->query('training'));
            
            if($data->isEmpty()){
                throw new RuntimeException('Nenhuma concessionária encontrada nessa cidade');
            }
            foreach($data as $unique){
                $count = $this->vacanciesCount->where('concessionaire_id', $unique->id)
                    ->where('trainings_id', $request->query('training'))
                    ->count();
                    
                $vacancies = $unique->trainingVacancies[0]->pivot->vacancies - $count;

                $unique->vacancies = $vacancies;
            }
            
            return $data;
        }

        throw new RuntimeException('Estado e cidade são necessários');
    }

    public function generatePassword($id)
    {
        $infos = $this->find($id);
        
        $singlePass = new SinglePassService();

        $data_SinglePass = [
            'name'     => $infos->fantasy_name,
            'email'    => $infos->email,
            'role'     => 'manager',
            'password' => "volkswagen{$infos->DN}",
        ];
        
        try{
            $response = $singlePass->postUser($data_SinglePass);
        }catch(RuntimeException $error){
            return [
                'message' => $error->getMessage(),
                'status'  => 401,
            ]; 
        }

        $infos->concessionaire_login_id = $response->user_id;

        $infos->save();

        return [
            'message'   => 'Senha criada com sucesso',
            'status' => 200
        ];
    }
}