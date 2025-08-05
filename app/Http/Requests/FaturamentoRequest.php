<?php

namespace App\Http\Requests;

use App\Faturamento;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;

class FaturamentoRequest extends Request
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
            'ano' => 'required',
            'mes' => 'required'
        ];
    }

    public function getValidatorInstance()
    {

        try {
            $validator = parent::getValidatorInstance();

            $validator->after(function () use ($validator) {
                $inputs = Input::all();

                $ano = $inputs['ano'];
                $mes = $inputs['mes'];

                $is_faturamento = Faturamento::getFaturamentoAnoMes($ano, $mes);
                if(!empty($is_faturamento)) {
                    $validator->errors()->add('ano', 'Não é possivel cadastrar faturamento para o mesmo mes e ano!');
                    $validator->errors()->add('mes', 'Não é possivel cadastrar faturamento para o mesmo mes e ano!');
                }else {
                    if ($ano == date('Y') && $mes < date('m')){
                        $validator->errors()->add('mes', 'Não é possivel cadastrar um mês anterior!');
                    }
                }
            });
        }catch (\Exception $e){
            exit("<pre>LINE: ".__LINE__." - EXECEPTION ".print_r($e->getMessage(), 1)."</pre>"); #debug-edersonsandre
        }


        return $validator;
    }

}
