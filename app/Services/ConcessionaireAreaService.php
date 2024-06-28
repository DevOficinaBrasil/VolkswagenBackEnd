<?php

namespace App\Services;

use App\Http\Repository\ConcessionaireAreaRepository;
use App\Http\Repository\ConcessionaireRepository;
use App\Http\Repository\TrainingRepository;
use App\Models\User;

class ConcessionaireAreaService
{
    public function __construct(
        protected TrainingRepository $repository,
        protected ConcessionaireAreaRepository $concessionaireAreaRepository,
    ){}
    
    public function trainings(string $id)
    {
        $data = $this->repository->especifyConcessionaire($id);

        return $data;
    }
    
    public function users(string $training, string $concessionaire)
    {
        $data = $this->concessionaireAreaRepository->getAllUsersOnTrainingByConcessionaire($training, $concessionaire);

        return $data;
    }

    public function updatePresence(string $training, string $user, string $concessionaire)
    {
        $data = $this->concessionaireAreaRepository->updatePresence($training, $user, $concessionaire);

        if($data){
            return [
                'status' => 200,
                'message' => 'Presença confirmada'
            ];
        }

        return [
            'status' => 401,
            'message' => 'Erro ao confirmar presença'
        ];
    }
}