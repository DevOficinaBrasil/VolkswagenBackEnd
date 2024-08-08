<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
    ){}
    
    public function getAll()
    {
        $data = $this->userRepo->all();

        return $data;
    }
    
    public function searchUser(array $searchParams)
    {
        foreach($searchParams as $term => $content){
            $column = $term;
            $value  = $content;
        }

        $data = $this->userRepo->search($column, $value);

        return $data;
    }
    
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