<?php

namespace App\Http\Requests;

use App\ArenaEquipamentos;
use App\Http\Requests\Request;
use App\LinhaCuidado;

class AgendasRequest extends Request
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
        $data = $this->all();

        $rules =  [
            'paciente' => 'required',
            'data' => 'required|date_format:"d/m/Y"',
            'hora' => 'required|date_format:"H:i"',
            'arena' => 'required',
            'linha_cuidado' => 'required',
            'estabelecimento' => 'required'
        ];

        if(!empty($data['id'])){
            unset($rules['data']);
        }

        if (!empty($data['linha_cuidado'])) {
            $linha_cuidado = (Object) LinhaCuidado::get($data['linha_cuidado']);

            if($linha_cuidado->especialidade == 2){
                $rules['tipo_atendimento'] = 'required';
                $rules['medico'] = 'required';
            }

            if($linha_cuidado->especialidade == 1){
                $rules['arena_equipamento'] = 'required';
                $rules['medico'] = 'required';
            }

        }

        return $rules;
    }
}
