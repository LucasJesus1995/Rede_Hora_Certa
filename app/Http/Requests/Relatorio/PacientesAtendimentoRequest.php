<?php


namespace App\Http\Requests\Relatorio;


use App\Http\Requests\Request;

class PacientesAtendimentoRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'periodo_inicial' => 'required|date_format:"d/m/Y"',
            'periodo_final' => 'required|date_format:"d/m/Y"',
        ];
    }
}
{

}