<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AtestadoRequest extends Request
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
            'atendimento' => 'required|exists:atendimento,id',
            'cid' => 'required|exists:cid,id',
            'empresa' => 'required|max:100',
            'hora_chegada' => 'required|date_format:"H:i"',
            'hora_saida' => 'required|date_format:"H:i"',
            'tempo_repouso' => 'required|numeric'
        ];
    }
}
