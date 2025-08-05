<?php

namespace App\Http\Requests\Admin\Relatorios;

use App\Http\Requests\Request;

class ReceitaArenaRequest extends Request
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
            'contrato' => 'required|max:100',
            'faturamento' => 'required',
        ];
    }
}
