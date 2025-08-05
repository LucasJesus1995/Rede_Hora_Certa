<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 07/02/19
 * Time: 11:48
 */

namespace App\Http\Helpers;


use App\Cidades;
use App\Estados;

class DataHelpers
{

    public static function getCidade($id = null)
    {
        $data = null;
        if (!is_null($id)) {
            $cidade = Cidades::get($id);

            if (!empty($cidade->id)) {
                $data = $cidade->nome;
            }
        }

        return $data;
    }


    public static function getCidadeIBGE($id = null)
    {
        $data = null;
        if (!is_null($id)) {
            $cidade = Cidades::get($id);

            if (!empty($cidade->id)) {
                $data = $cidade->ibge;
            }
        }

        return $data;
    }

    public static function getCidadeEstadoSigla($id)
    {
        $data = null;
        if (!is_null($id)) {
            $cidade = Cidades::get($id);

            if (!empty($cidade->estado)) {
                $estado = Estados::find($cidade->estado);

                if (!empty($estado->id)) {
                    $data = $estado->sigla;
                }
            }
        }

        return $data;
    }

    public static function getOfertaStatus($key = null)
    {
        $data[1] = "NAO ABERTA";
        $data[2] = "APROVADA";
        $data[3] = "ABERTA";
        $data[4] = "REQUER CORRECAO";
        $data[5] = "REQUER DESMARCACAO";
        $data[6] = "DESMARCADO";
        $data[7] = "REQUER IMPEDIMENTO";
        $data[8] = "IMPEDIDO";
        $data[9] = "REQUER MEDICO";
        $data[13] = "REMARCADO";
        $data[14] = "REQUER REMARCACAO";
        $data[15] = "CONFIRMAÇÃO PENDENTE";
        $data[17] = "EXCLUIDO";
        $data[18] = "ABERTURA CANCELADA";

        if (!is_null($key)) {
            return (array_key_exists($key, $data)) ? $data[$key] : $key;
        }

        return $data;
    }

    public static function getNatureza($key = null)
    {
        $data[1] = "EXAME";
        $data[2] = "CONSULTA";
        $data[3] = "CIRURGIA";
        $data[4] = "DIAGNOSTICO";

        if (!is_null($key)) {
            return (array_key_exists($key, $data)) ? $data[$key] : $key;
        }

        return $data;
    }


    public static function getPeriodo($key = null)
    {
        $data[1] = "MANHA";
        $data[2] = "TARDE";
        $data[3] = "NOITE";

        if (!is_null($key)) {
            return (array_key_exists($key, $data)) ? $data[$key] : $key;
        }

        return $data;
    }

    public static function getClassificacao($key = null)
    {
        $data['Cirurgia Geral'][1] = "Retorno de Consulta";
        $data['Cirurgia Geral'][2] = "Cirurgia Hérnia";
        $data['Cirurgia Geral'][3] = "Hérnia - 1º Vez";
        $data['Oftalmologia'][4] = "Cirurgia Pterírgio";
        $data['Oftalmologia'][6] = "Cirurgia Catarata";
        $data['Oftalmologia'][7] = "Oftalmo - 1° vez";
        $data['Oftalmologia'][14] = "Biometria";
        $data['Oftalmologia'][15] = "Retorno - Consulta";
        $data['Oftalmologia'][16] = "Yag Laser";
        $data['Cardiologia'][8] = "Holter";
        $data['Cardiologia'][9] = "Mapa";
        $data['Cardiologia'][10] = "Teste";
        $data['Colonoscopia'][11] = "Preparo Colono";
        $data['Doopler'][12] = "Consulta (Retorno Cirurgia)";
        $data['Pequenas Cirurgias'][17] = "Pequenas 1º Vez";
        $data['Pequenas Cirurgias'][18] = "Retorno - Consulta";
        $data['Pequenas Cirurgias'][38] = "Pequenas Cirurgia";
        $data['Ultrassom'][19] = "USG Ocular";
        $data['Urologia'][20] = "Fimose Cirurgia";
        $data['Urologia'][21] = "Fimose 1º Vez";
        $data['Urologia'][22] = "Urologia 1º Vez";
        $data['Urologia'][23] = "Retorno - Consulta";
        $data['Urologia'][24] = "Vasectomia Cirurgia";
        $data['Urologia'][25] = "Vasectomia 1º Vez";
        $data['Urologia'][26] = "Preparo Estudo Urodinâmico";
        $data['Urologia'][27] = "Coleta Urodinâmica";
        $data['Vascular'][28] = "Retorno – Consulta";
        $data['Vascular'][29] = "Varizes Cirurgia";
        $data['Vascular'][30] = "Angiologia Varizes 1º Vez";
        $data['Vascular'][31] = "Cirurgia Varizes 1°vez";
        $data['Dermatologia'][32] = "Dermato 1°vez";
        $data['Dermatologia'][33] = "Retorno – Consulta";
        $data['Mamografia'][34] = "50 á 69 anos CID Z12.3";
        $data['Mamografia'][35] = "RT 35 á 69 anos CID Z12.3";
        $data['Mamografia'][36] = "50 á 69 anos";
        $data['Mamografia'][37] = "RT Sem idade";

        if (!is_null($key)) {
            return (array_key_exists($key, $data)) ? $data[$key] : $key;
        }

        return $data;
    }

    public static function getClassificacaoKeys()
    {
        $classificacoes = [];

        $data = self::getClassificacao();
        foreach ($data AS $k => $classficacao) {
            if (is_array($classficacao)) {
                foreach ($classficacao AS $_k => $row) {
                    $classificacoes[$_k] = $k . " - " . $row;
                }
            }
        }

        return $classificacoes;
    }

    public static function getClassificacaoDescricao($key)
    {

        if (intval($key) > 0) {
            $classficacoes = self::getClassificacao();
            foreach ($classficacoes AS $k => $classficacao) {
                if (is_array($classficacao)) {
                    foreach ($classficacao AS $_k => $row) {
                        if ($_k == $key) {
                            return $k . " - " . $row;
                        }
                    }
                }
            }
        }
    }

    public static function getClassificacaoEspecialidade(){
        $data['1'] = 'Pré';
        $data['2'] = 'Retorno';
        $data['3'] = '1º Pós';
        $data['4'] = 'Cirurgia';
        $data['5'] = 'Outros Pós';


        return $data;
    }


}