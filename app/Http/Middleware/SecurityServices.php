<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiServices;
use App\Pacientes;
use App\PacientesMobile;
use Closure;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class SecurityServices
{

    use Helpers;

    private $request;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $uri = $this->request->path();

        try {
            if($uri == 'services'){
                $this->validaLogin();
            }else{
                $this->validaClient();
            }

            return $next($request);
        } catch (\Exception $e){

            return $this->response->errorForbidden($e->getMessage());
        }
    }

    private function validaLogin()
    {
        $key = $this->request->header('key');
        $token = $this->request->header('token');

        if(empty($key))
            throw new \Exception("Não foi encontrado KEY para autenticação");

        if(empty($token))
            throw new \Exception("Não foi encontrado TOKEN para autenticação");

        $access = ApiServices::getClientLogin();
        if(!array_key_exists($key, $access))
            throw new \Exception("Acesso inválido, KEY não cadastrada!");

        if($access[$key] != $token)
            throw new \Exception("Acesso inválido, KEY e TOKEN não vinculado!");

    }

    private function validaClient()
    {
        $key = $this->request->header('key');
        $token = $this->request->header('token');

        if(empty($key))
            throw new \Exception("Não foi encontrado KEY para autenticação");

        if(empty($token))
            throw new \Exception("Não foi encontrado TOKEN para autenticação");


        $paciente = PacientesMobile::getByCNSEToken($key, $token);
        if(!$paciente)
            throw new \Exception("Paciente não encontrado!");


        $this->request->session()->put('paciente', $paciente->id);
    }
}
