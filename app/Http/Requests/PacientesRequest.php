<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PacientesRequest extends Request
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
            'nome_social' => 'max:80',
            'ativo' => 'required',
            'cidade' => 'required',
            'sexo' => 'required',
            'mae' => 'required',
            'estabelecimento' => 'required',
            'email' => 'email',
            'nascimento' => 'required|date_format:"d/m/Y"',
            'cns' => 'required|numeric|min:15|unique:pacientes,cns,'.$this->get('id'),
        ];
    }
}
