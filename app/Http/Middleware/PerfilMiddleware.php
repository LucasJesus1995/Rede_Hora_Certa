<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PerfilMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::check()) {
            if (!Auth::user()->active) {

                $rules =[
                    'active' => 'required|boolean:true'
                ];

                $validation = Validator::make(Auth::user()->toArray(), $rules, []);
                $validation->getMessageBag()->add('email', 'Usuário sem autorização para acessar o sistema. ');

                Auth::logout();

                return redirect()->to('/')->withInput()->withErrors($validation);
            }
        }

        if((empty(Auth::user()) && empty(Auth::user()->level)) || $request->getRequestUri() == '/auth/perfil'){
            return $next($request);
        }else{
            if(Auth::user()->level == 10 && !strstr($request->getRequestUri(), 'admin/linha-cuidado/arena') && !strstr($request->getRequestUri(), 'admin/linha-cuidado/profissionais')){

                if(empty(Cookie::get('doctor'))){
                    return redirect("/auth/perfil");
                }
            }
        }

        return $next($request);
    }
}
