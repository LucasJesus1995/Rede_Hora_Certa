<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ArenasRequest extends Request
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
            'responsavel' => 'required',
            'nome' => 'required|max:100',
            'endereco' => 'max:100',
            'numero' => 'max:10',
            'complemento' => 'max:20',
            'bairro' => 'max:30',
            'cnes' => 'max:20',
            'estado' => 'max:2',
            'telefone' => 'min:10',
            'celular' => 'min:10',
            'ativo' => 'required',
            'linha_cuidado' => 'array|required',
            'unidade' => 'required',
        ];
    }
}
