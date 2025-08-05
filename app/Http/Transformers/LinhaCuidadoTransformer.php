<?php

namespace App\Http\Transformers;

use App\LinhaCuidado;
use League\Fractal\TransformerAbstract;

class LinhaCuidadoTransformer extends TransformerAbstract
{

    public function transform(LinhaCuidado $data)
    {

        $data =  [
            'id'            => (int) $data->id,
            'nome'          =>  $data->nome,
            'abreviacao'    =>  $data->abreviacao,
            'links' => [
                [
                    'uri' => $_SERVER['API_URI'] .'linha-cuidado/' . $data->id,
                ],
            ],
        ];


        return $data;
    }

}