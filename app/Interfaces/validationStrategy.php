<?php

namespace App\Interfaces;

use App\Services\AccessService;
use Illuminate\Http\Request;

interface validationStrategy
{
    public function validate(Request $request, AccessService $service);
}