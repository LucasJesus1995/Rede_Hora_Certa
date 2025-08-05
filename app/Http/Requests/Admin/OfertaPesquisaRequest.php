<?php

namespace App\Http\Requests\Admin;

use App\Http\Helpers\DataHelpers;
use App\Http\Requests\Request;

class OfertaPesquisaRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'data-inicial' => 'required|date_format:"d/m/Y"',
            'data-final' => 'required|date_format:"d/m/Y"',
        ];

        return $rules;
    }
}
