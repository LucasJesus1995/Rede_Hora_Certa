<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 29/12/16
 * Time: 15:42
 */

namespace App\Http\Requests;


class CirugiaLinhaCuidadoRequest extends Request
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
        ];
    }
}