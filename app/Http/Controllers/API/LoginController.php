<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\LoginRequest;
use App\Http\Transformers\LoginPacienteTransformer;
use App\Pacientes;
use App\PacientesMobile;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends BaseController
{

    /**
     * @Resource("Users", uri="/users")
     */
    public function postLogin(Request $request){
        $data = $request->all();
        $login_request = new LoginRequest();

        $validator = app('validator')->make($data, $login_request->rules());

        if ($validator->fails()) {
            $error = current(current($validator->errors()->toArray()));

            throw new BadRequestHttpException($error);
        }

        $paciente = Pacientes::getByCNSAndCPF($request->get('cns'), $request->get('cpf'));
        if(!$paciente)
            throw new BadRequestHttpException("Paciente não vinculado ao código CNS: {$request->get('sus')} e CPF: {$request->get('cpf')}");

        $paciente_mobile = PacientesMobile::__save($paciente);

        return $this->response->item($paciente, new LoginPacienteTransformer);
    }

}
