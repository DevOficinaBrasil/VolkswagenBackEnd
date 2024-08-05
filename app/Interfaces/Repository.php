<?php

namespace App\Interfaces;

interface Repository
{
    public function getAll();

    public function findUnique(int $id);
}