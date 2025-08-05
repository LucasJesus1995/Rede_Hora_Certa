<?php

namespace App\Http\Requests\Admin\Atendimento;

use App\Condutas;
use App\Http\Helpers\AtendimentoHelpers;
use App\Http\Requests\Request;
use App\TipoAtendimento;

class AtendimentoCondutaRequest extends Request
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
        $condutas = Condutas::Combo();

//        $rules['tipo_atendimento'] = 'required|in:' . implode(",", array_keys(TipoAtendimento::Combo()));
        $rules['conduta'] = 'required|in:' . implode(",", array_keys($condutas));

        $rules['conduta_descricao'] = 'max:200';

        if (!empty($data['conduta_secundaria'])) {
            $rules['conduta_secundaria'] = 'in:' . implode(",", array_keys($condutas));
        }

        if (!empty($data['conduta']) && !empty($data['conduta_secundaria'])) {
            $rules['conduta_secundaria'] = 'different:conduta|in:' . implode(",", array_keys($condutas));
        }

        if (!empty($data['conduta'])) {
            $conduta = Condutas::find($data['conduta']);
            if (!empty($conduta->id) && $conduta->valida_regulacao) {
                $rules['conduta_descricao'] = 'required|max:200';
                $rules['conduta_regulacao'] = 'required|in:' . implode(",", array_keys($condutas));
            }
        }

        return $rules;
    }
}
