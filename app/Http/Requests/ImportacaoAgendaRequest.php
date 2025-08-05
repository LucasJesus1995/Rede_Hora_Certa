<?php

namespace App\Http\Requests;

use App\ArenaEquipamentos;

class ImportacaoAgendaRequest extends Request
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
        $data['file'] = 'required|mimes:txt';
        $data['data'] = 'required';
        $data['arena'] = 'required';
        $data['linha_cuidado'] = 'required';

        if (!empty($this->arena) && !empty(ArenaEquipamentos::getByArena($this->arena))) {
            //$data['equipamento'] = 'required';
        }

        return $data;
    }
}
