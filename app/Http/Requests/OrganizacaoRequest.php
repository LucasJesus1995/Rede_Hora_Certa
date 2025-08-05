<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 04/09/18
 * Time: 17:22
 */

namespace App\Http\Requests;


class OrganizacaoRequest extends Request
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
            'codigo' => 'required|numeric',
            'descricao' => 'required|max:100',
            'sub_grupo' => 'required|numeric',
        ];
    }
}