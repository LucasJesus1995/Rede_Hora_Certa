<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProdutosRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoria'         => 'required',
            'nome'              => 'required|max:200',
            'codigo'            => 'required|max:20',
            'unidade_medida'    => 'required',
            'tipo_apresentacao'    => 'required',
        ];
    }
}
