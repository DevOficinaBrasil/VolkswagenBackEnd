<?php

namespace App\Traits;

trait GenerateParamsNotification
{
    public function registerUser($request)
    {
        $params = [
            'cf_noticias_da_oficina_vw' => 'S',
            'cf_primeiro_nome' => explode(' ', $request->name)[0],
            'state' => $request->state,
            'city' => $request->city,
            'cf_email_contato' => $request->email,
            'cf_noticias_da_oficina_vw_cadastro_novo' => 'S',
        ];

        return $params;
    }

    public function trainingCreate($trainingId, $concessionaire)
    {
        $params = [
            'cf_inscricao_treinamento_volkswagen' => $trainingId,
            'cf_noticias_da_oficina_vw_nome_concessionaria' => $concessionaire->fantasy_name,
            'cf_noticias_da_oficina_vw_bairro' => $concessionaire->complement,
            'cf_noticias_da_oficina_vw_cep' => $concessionaire->cep,
            'cf_noticias_da_oficina_vw_cidade' => $concessionaire->city,
            'cf_noticias_da_oficina_vw_endereco' => $concessionaire->street,
            'cf_noticias_da_oficina_vw_estado' => $concessionaire->state,
            'cf_noticias_da_oficina_vw_numero' => $concessionaire->number,
            'cf_noticias_da_oficina_vw_inscrito_formato' => 'O' // P ou O
        ];

        return $params;
    }
}