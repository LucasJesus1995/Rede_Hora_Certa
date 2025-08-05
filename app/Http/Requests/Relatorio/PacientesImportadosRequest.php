<?php


namespace App\Http\Requests\Relatorio;


use App\Http\Requests\Request;

class PacientesImportadosRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data' => 'required|date_format:"d/m/Y"',
//            'arena' => 'required',
        ];
    }
}
{

}