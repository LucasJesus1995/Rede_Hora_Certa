<?php

namespace App\Http\Requests\Relatorio;

use App\Http\Requests\Request;

class RelatorioContasConsultaRequest extends Request
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
            'periodo_inicial' => 'required|date_format:"d/m/Y"',
            'periodo_final' => 'required|date_format:"d/m/Y"|after:periodo_inicial|valid_date_range:periodo_inicial,0',
        ];

    }
}
