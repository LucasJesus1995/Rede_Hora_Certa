<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertaRelatorioRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'mes' => 'required'
        ];

        return $rules;
    }
}
