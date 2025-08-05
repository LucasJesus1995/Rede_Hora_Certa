<?php

namespace App\Http\Transformers;

use App\Agendas;
use League\Fractal\TransformerAbstract;

class AgendaTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'linha_cuidado','arenas'
    ];


    public function transform(\App\Agendas $data)
    {
        return [
                'id'            => (int) $data->id,
                'agendamento'          => $data->data,
                'meta' => [
                    'atualizacao'          => $data->updated_at,
                    'criacao'          => $data->created_at,
                    'links' => [
                        [
                            'uri' => $_SERVER['API_URI'] .'atendimentos/' . $data->id,
                        ],
                    ],
                ],
            ];

    }


    public function includeLinhaCuidado(Agendas $agenda){
        $linha_cuidado = $agenda->linha_cuidados;

        return $this->item($linha_cuidado, new LinhaCuidadoTransformer);
    }

    public function includeArenas(Agendas $agenda){
        $arenas = $agenda->arenas;

        return $this->item($arenas, new ArenasTransformer);
    }

}