<?php

namespace App\Http\Requests\Relatorio;

use App\Http\Requests\Request;

class ProducaoMedicosRequest extends Request
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'ano' => 'required',
            'mes' => 'required'
        ];
    }
}
