<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UsuariosRequest extends Request
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
        $rules = [
            'name'             => 'required|between:2,35',
            'email'            => 'required|email|max:255|unique:users,email,'.$this->get('id'),
            'active'         => 'required',
            'lote'         => 'required',
            'level'         => 'required',
        ];

        if(empty($this->get('id')))
            $rules['password'] = 'required|min:5';


        return $rules;
    }
}
