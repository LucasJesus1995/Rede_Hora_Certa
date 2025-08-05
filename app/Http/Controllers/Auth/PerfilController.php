<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;

class PerfilController extends Controller
{

    public function getIndex()
    {
        $view = View("auth.perfil.index");

        return $view;
    }

    public function postIndex(Requests\AuthPerfilRequest $request) {
        $data = $request->all();

        $request->session()->set('digitador', [
            'arena' => $data['arena'],
            'linha_cuidado' => $data['linha_cuidado'],
            'doctor' => $data['doctor']
        ]);

        Cookie::queue('arena', $data['arena']);
        Cookie::queue('linha_cuidado', $data['linha_cuidado']);
        Cookie::queue('doctor', $data['doctor']);

        return redirect('/admin');
    }

}
