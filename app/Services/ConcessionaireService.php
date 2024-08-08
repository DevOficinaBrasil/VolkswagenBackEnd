<?php

namespace App\Services;

use App\Http\Repository\ConcessionaireRepository;
use App\Models\TrainingUser;
use Exception;
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

    public function generatePassword(string $name, string $email, string $DN)
    {
        $singlePass = new SinglePassService();

        $data_SinglePass = [
            'name'     => $name,
            'email'    => $email,
            'role'     => 'manager',
            'password' => "volkswagen{$DN}",
        ];
        
        try{
            $response = $singlePass->postUser($data_SinglePass);
        }catch(RuntimeException $error){
            return [
                'message' => $error->getMessage(),
                'status'  => false,
            ]; 
        }

        return [
            'iD' => $response->user_id,
            'status'  => true,
        ];
    }

    public function addNewConcessionaire(array $singlePass, array $address, Request $request)
    {
        if(!$singlePass['status'] || !$address['status']){
            return throw new Exception('Erro ao adicionar');
        }

        $params = [
            'CNPJ'                    => $request->cnpj,
            'fantasy_name'            => $request->fantasy,
            'manager_name'            => $request->manager,
            'certify_name'            => $request->certify,
            'email'                   => $request->email,
            'phone'                   => $request->phone,
            'DN'                      => $request->dn,
            'concessionaire_login_id' => $singlePass['iD'],
            'concessionaire_address'  => $address['iD'],
        ];

        $data = $this->concessionaireRepo->store($params);
    }
}