<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertasRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'data' => 'required|date_format:"d/m/Y"',
            'arena' => 'required',
            'linha_cuidado' => 'required',
            'profissional' => 'required',
            'periodo' => 'required|in:' . implode(",", array_keys(DataHelpers::getPeriodo())),
            'hora_inicial' => 'required|date_format:"H:i"',
            'hora_final' => 'required|date_format:"H:i"',
            'status' => 'required|in:' . implode(",", array_keys(DataHelpers::getOfertaStatus())),
            'natureza' => 'required|in:' . implode(",", array_keys(DataHelpers::getNatureza())),
            'quantidade' => 'required|digits_between:1,4',
            'observacao' => 'max:300',
            'repetir' => 'required',
        ];

        return $rules;
    }
}
