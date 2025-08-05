<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProcedimentosRequest extends Request
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
            'nome' => 'required|max:100',
            'ativo' => 'required',
            'ordem' =>'numeric',
            'quantidade' =>'numeric',
            'saldo' =>'numeric',
            'maximo' =>'numeric',
            'cbo' =>'numeric',
            'sus' =>'numeric',
            'multiplicador' =>'numeric|max:10',
            'linha_cuidado' => 'required|array',
            'autorizacao' => 'required',
            'obrigar_preenchimento_apac' => 'required',
        ];
    }
}
