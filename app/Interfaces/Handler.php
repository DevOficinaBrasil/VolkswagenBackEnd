<?php


namespace App\Interfaces;

use App\Services\AccessService;
use Illuminate\Http\Request;

interface Handler
{
    public function setNext(Handler $handler): Handler;

    public function handle(Request $request, AccessService $service, array &$context);
}