<?php

namespace App\Http\Requests;

class ExamesRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|max:60',
            'codigo' =>'required|numeric',
            'ativo' => 'required',
        ];
    }
}
