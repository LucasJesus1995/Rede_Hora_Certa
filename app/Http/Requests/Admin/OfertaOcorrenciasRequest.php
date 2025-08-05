<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertaOcorrenciasRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'oferta' => 'required',
            'status' => 'required|in:' . implode(",", array_keys(DataHelpers::getOfertaStatus())),
            'descricao' => 'required|min:10|max:300',
        ];

        return $rules;
    }
}
