<?php

namespace App\Handlers;

use App\Interfaces\Handler;
use App\Services\AccessService;
use Illuminate\Http\Request;

abstract class AbstractHandler implements Handler
{
    private ?Handler $nextHandler = null;

    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    public function handle(Request $request, AccessService $service, array &$context)
    {
        if($this->nextHandler){
            return $this->nextHandler->handle($request, $service, $context);
        }

        return null;
    }
}