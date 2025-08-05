<?php

namespace App\Http\Transformers;

use App\PacientesMobile;
use League\Fractal\TransformerAbstract;

class LoginPacienteTransformer extends TransformerAbstract
{

    public function transform(\App\Pacientes $data)
    {
        $_data['paciente'] = [
                            'id'            => (int) $data->id,
                            'nome'          => $data->nome,
                            'cns'          => $data->cns,
                            'cpf'          => $data->cpf,
                            'sexo'          => $this->sexoConverter($data->sexo),
                            'nascimento'          => $data->nascimento,
                            'atualizacao'          => $data->updated_at,
                            'criacao'          => $data->created_at,
                            'links' => [
                                [
                                    'uri' => $_SERVER['REQUEST_URI'] .'/pacientes/' . $data->id,
                                ],
                            ],
                        ];

        $acesso = PacientesMobile::getByPaciente($data->id);

        $_data['acesso'] = [
            'token' => $acesso->token,
            'validade' => $acesso->validade,
            'atualizacao'          => $acesso->updated_at,
            'criacao'          => $acesso->created_at,
        ];

        return $_data;
    }

    public function sexoConverter($sexo){
        return ($sexo == 2) ? "F" : "M";
    }

}