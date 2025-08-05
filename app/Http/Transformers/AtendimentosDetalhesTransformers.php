<?php

namespace App\Http\Transformers;

use App\Atendimentos;
use App\Http\Helpers\Util;
use League\Fractal\TransformerAbstract;

class AtendimentosDetalhesTransformers  extends TransformerAbstract
{

    protected $defaultIncludes = [
        'laudos'
    ];

    public function transform(Atendimentos $data)
    {

        $data =  [
            'id'            => (int) $data->id,
            'preferencial'            => (boolean) $data->preferencial,
            'status'            => Util::StatusAgenda($data->status),
            'sala'            =>  $data->sala,
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

        return $data;
    }

    public function includeLaudos(Atendimentos $atendimentos){
        $laudos = $atendimentos->laudos;

        return $this->collection($laudos, new LaudosTransformer);
    }



}