<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 07/07/15
 * Time: 20:39
 */

namespace App\Http\Helpers;


use Zend\Filter\Digits;

class Mask {

    public static function Mask($val, $mask){
        $maskared = '';
        $k = 0;

        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#')  {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];

            } else {
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }

    return $maskared;
    }

    public static function Cpf($cpf = null){
        if($cpf){
            return self::Mask($cpf,'###.###.###-##');
        }

        return null;
    }


    public static function Cep($cep = null){
        if($cep){
            return self::Mask($cep,'#####-###');
        }

        return null;
    }

    public static function telefone($celular)
    {
        $digits = new Digits();

        $celular = $digits->filter($celular);
        switch (strlen($celular)){
            case 8 :
                $_celular = self::Mask($celular,'####-####');
                break;
            case 10 :
                $_celular = self::Mask($celular,'(##) ####-####');
                break;
            case 11 :
                $_celular = self::Mask($celular,'(##) # ####-####');
                break;
            default :
                $_celular = $celular;
                break;

        }

        return $_celular;
    }

    public static function ProcedimentoSUS($procedimento_sus = null)
    {
        if($procedimento_sus){
            return self::Mask($procedimento_sus,'##.##.##.###-#');
        }
    }

    public static function SUS($sus = null)
    {
        if($sus){
            return self::Mask($sus,'### #### #### ####');
        }
    }

    public static function CIES($sus = null)
    {
        if($sus){
            return self::Mask($sus,'#### #### #### ####');
        }
    }

    public static function RG($rg = null)
    {
        if($rg){
            return self::Mask($rg,'##.###.###-#');
        }
    }


    public static function CodigoProcedimento($data = null)
    {
        if($data){
            return self::Mask($data,'##.##.##.###-#');
        }

    }
} 