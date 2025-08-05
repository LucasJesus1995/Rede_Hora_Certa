<?php


namespace App\Http\Helpers;


class UsuarioHelpers
{

    public static function getNivel(){
        return \App\Http\Helpers\Util::getNivel();
    }

    public static function isNivelCirurgico(){
        return in_array(\App\Http\Helpers\Util::getNivel(), [19]);
    }

}