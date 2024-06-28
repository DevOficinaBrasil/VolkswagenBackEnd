<?php


namespace App\Adapter;

use App\Interfaces\EmailSenderInterface;
use App\Services\RdStationService;

class RdStationAdapter implements EmailSenderInterface
{
    private $RdStationService;

    public function __construct()
    {
        $this->RdStationService = new RdStationService();
    }

    public function config(array $request)
    {
        $this->RdStationService->params($request);
    }

    public function send(string $endpoint, string $options = '')
    {
        $this->RdStationService->post($endpoint, $options);
    }
}