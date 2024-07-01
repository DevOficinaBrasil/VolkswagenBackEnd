<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
    ){}
    
    public function allInfos(string $id)
    {
        $data = $this->userRepo->find($id);

        return $data;
    }

    
    public function updateUser(Request $data)
    {
        $userData = $this->userRepo->update($data);

        
        return $userData;

    }
}