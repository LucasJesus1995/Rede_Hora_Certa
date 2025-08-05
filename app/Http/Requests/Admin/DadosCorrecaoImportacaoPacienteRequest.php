<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class DadosCorrecaoImportacaoPacienteRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|mimes:txt',
        ];
    }
}
