<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function all();

    public function find($id);
    
    public function create($user_DATA, $login_ID, $addres_ID);

    public function update($user_DATA, $login_ID, $addres_ID);

    public function search(string $search, string $argument);
}