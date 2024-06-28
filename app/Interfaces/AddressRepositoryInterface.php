<?php

namespace App\Interfaces;

interface AddressRepositoryInterface
{
    public function search($attr, $term);

    public function create($value, int $foreign_key = null);

    public function existOrCreate($value, int $foreign_key = null);
}