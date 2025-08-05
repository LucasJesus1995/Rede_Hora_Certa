<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProdutosFornecedoresRequest extends Request
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
            'razao_social'      => 'required|max:200',
            'cnpj'              => 'required',
        ];
    }
}
