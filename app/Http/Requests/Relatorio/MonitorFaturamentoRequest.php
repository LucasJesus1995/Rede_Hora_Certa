<?php

namespace App\Http\Requests\Relatorio;

use App\Http\Requests\Request;

class MonitorFaturamentoRequest extends Request
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'contrato' => 'required',
            'faturamento' => 'required'
        ];
    }
}
