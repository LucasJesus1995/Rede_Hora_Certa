<?php

namespace App\Http\Requests\Importacao;

use App\Http\Requests\Request;

class OfertaSaveRequest extends Request
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
            'lote' => 'required',
            'ano' => 'required|date_format:"Y"',
            'mes' => 'required|date_format:"m"',
            'arena' => 'required',
            'linha_cuidado' => 'required',
            'qtd' => 'numeric',
        ];
    }
}
