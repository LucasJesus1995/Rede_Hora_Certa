<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertaAprovacaoRequest extends Request
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
            'data_aprovacao' => 'required|date_format:"d/m/Y"',
        ];

        return $rules;
    }
}
