<?php

namespace App\Http\Transformers;

use App\Arenas;
use App\AtendimentoLaudo;
use App\Cid;
use App\Http\Helpers\Util;
use League\Fractal\TransformerAbstract;

class LaudosTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'cids'
    ];

    public function transform(AtendimentoLaudo $data)
    {

        $status_laudo = Util::getLaudoResultados();
        $status_biopsia = Util::statusLaudo();

        $data =  [
            'id'                    =>  (int) $data->id,
            'cid'                   =>  $data->cid,
            'pdf'                   =>  $data->url,
            'descricao'             =>  urlencode(strip_tags(urldecode($data->descricao))),
            'resultado'             =>  array_key_exists($data->resultado, $status_laudo) ? $status_laudo[$data->resultado] : null,
            'biopsia'               =>  $data->biopsia,
            'resultado_biopsia'     =>  $data->resultado_biopsia,
            'status_biopsia'        =>  array_key_exists($data->status_biopsia, $status_biopsia) ? $status_biopsia[$data->status_biopsia] : null,
            'meta' => [
                'atualizacao'       => $data->updated_at,
                'criacao'           => $data->created_at,
                'links' => [
                    [
                        'uri'       => $_SERVER['API_URI'] .'atendimento-laudo/' . $data->id,
                    ],
                ],
            ],
        ];

        return $data;
    }

    public function includeCids(AtendimentoLaudo $laudo){
        $cids = $laudo->cids;

        return count($cids) ? $this->item($cids, new CidTransformer) : null;
    }

}