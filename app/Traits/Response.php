<?php

namespace App\Traits;

trait Response
{
    public function response($info)
    {
        return response()->json($info['data'], $info['status']);
    }
}