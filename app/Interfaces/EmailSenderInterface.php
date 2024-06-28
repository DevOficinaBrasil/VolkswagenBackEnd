<?php

namespace App\Interfaces;

interface EmailSenderInterface
{
    public function config(array $request);

    public function send(string $endpoint, string $options = '');
}