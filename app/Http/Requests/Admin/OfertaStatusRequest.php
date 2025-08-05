<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertaStatusRequest extends Request
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
            'aberta' => 'required|in:0,1',
        ];

        return $rules;
    }
}
