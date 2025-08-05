<?php

namespace App\Http\Requests\Admin\Atendimento;

use App\Http\Requests\Request;

class AtendimentoAnexosRequest extends Request
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
            'tipo' => 'required',
            'arquivo' => 'required|mimes:pdf',
            'anotacao' => 'max:200',
        ];
    }
}
