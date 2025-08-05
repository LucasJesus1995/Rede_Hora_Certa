<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 18/08/17
 * Time: 18:22
 */

namespace App\Http\Helpers;


class ApiServices
{
    public static function getClientLogin(){
        $data['digite-saude'] = 'cf11210004621ec9efe862ceaeefe3e45c6af2ba';

        return $data;
    }

    public static function mountReturnErro($message, $code){
        $data['status'] = false;
        $data['erro'] = $message;

        return $data;
    }
}