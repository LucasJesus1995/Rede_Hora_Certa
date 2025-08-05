<?php

namespace App\Http\Transformers;

use App\Cid;
use League\Fractal\TransformerAbstract;

class CidTransformer extends TransformerAbstract
{

    public function transform(Cid $data)
    {

        $data =  [
            'id'            => (int) $data->id,
            'codigo'          =>  $data->codigo,
            'descricao'    =>  $data->abreviacao,
            'links' => [
                [
                    'uri' => $_SERVER['API_URI'] .'cid/' . $data->id,
                ],
            ],
        ];


        return $data;
    }

}