<?php

namespace App\Http\Requests;

use App\ArenaEquipamentos;
use App\Arenas;
use App\LinhaCuidado;

class ImportacaoAgendaPDFRequest extends Request
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
        $data['file'] = 'required|mimes:pdf';
        $data['data'] = 'required';
        $data['arena'] = 'required';
        $data['linha_cuidado'] = 'required';

//        if (!empty($this->arena)) {
//            if (!empty(ArenaEquipamentos::getByArena($this->arena))) {
//                $data['equipamento'] = 'required';
//            }
//        }

        if (!empty($this->linha_cuidado)) {
            $linha_cuidado = (Object) LinhaCuidado::get($this->linha_cuidado);

            if($linha_cuidado->especialidade == 2){
                $data['tipo_atendimento'] = 'required';
                $data['medico'] = 'required';
            }

            if($linha_cuidado->especialidade == 1){
                $data['equipamento'] = 'required';
                $data['medico'] = 'required';
            }

        }

        return $data;
    }
}
