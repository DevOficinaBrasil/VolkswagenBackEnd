<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RdStationService
{
    private $token;
    private $params;

    public function __construct(
        private $url = "https://api.rd.services",
    ){}

    public function getToken()
    {
        $data = Http::asForm()->post("{$this->url}/auth/token", [
            'client_id' => '26a3266f-c022-449c-b91e-c10ed7f23d8d',
            'client_secret' => '08bffb4411764945a5f1ee4566fa875e',
            'refresh_token' => 'wAZ1wfzM1NpWsXyxNZL9_t9jIJD4hVRa2U6X1ledx1k',
        ])->object();

        $this->token = $data->access_token;
    }

    public function params(array $data = [])
    {
        $params = [ 
            [
                'category' => 'communications',
                'type' => 'consent',
                'status' => 'granted' 
            ]
        ];
        
        $data['legal_bases'] = $params;

        $this->params = $data;
    }

    public function post(string $endpoint, string $options)
    {
        $this->getToken();
        
        $response = Http::withToken($this->token)->patch("{$this->url}{$endpoint}/email:{$options}", $this->params);
    }
}