<?php

namespace App\Http\Transformers;

use App\Arenas;
use League\Fractal\TransformerAbstract;

class ArenasTransformer extends TransformerAbstract
{

    public function transform(Arenas $data)
    {

        $data =  [
            'id'            => (int) $data->id,
            'nome'          =>  $data->nome,
            'endereco'    =>  $data->endereco,
            'numero'    =>  $data->numero,
            'bairro'    =>  $data->bairro,
            'telefone'    =>  $data->telefone,
            'links' => [
                [
                    'uri' => $_SERVER['API_URI'] .'arenas/' . $data->id,
                ],
            ],
        ];


        return $data;
    }

}