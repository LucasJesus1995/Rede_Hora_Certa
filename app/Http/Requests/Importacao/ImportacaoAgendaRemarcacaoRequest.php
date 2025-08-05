<?php

namespace App\Http\Requests\Importacao;

use App\Http\Requests\Request;

class ImportacaoAgendaRemarcacaoRequest extends Request
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'data' => 'required|date_format:"d/m/Y"',
            'file' => 'required|mimes:xlsx',
            'arena' => 'required',
            'linha_cuidado' => 'required',
        ];
    }
}
