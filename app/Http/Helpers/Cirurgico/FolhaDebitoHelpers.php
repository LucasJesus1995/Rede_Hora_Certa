<?php

namespace App\Http\Helpers\Cirurgico;


class FolhaDebitoHelpers
{

    public static function getFolhaDebitoMaterial($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 49 :
            case 9 :
                $data[] = "Agua destilada 10ml";
                $data[] = "Agulha 13 x 4,5";
                $data[] = "Agulha 30x7";
                $data[] = "Agulha 40x12";
                $data[] = "Cateter oxigênio";
                $data[] = "Cefalotina";
                $data[] = "Dipirona";
                $data[] = "Eletrodo descartavel";
                $data[] = "Equipo macrogotas";
                $data[] = "Fio algodão";
                $data[] = "Fio de Nylon 5";
                $data[] = "Jelco nº";
                $data[] = "Lâmina bisturi nº 11";
                $data[] = "Lâmina bisturi nº 15";
                $data[] = "Luva cirurgica nº 6,5";
                $data[] = "Luva cirurgica nº 7";
                $data[] = "Luva cirurgica nº 7,5";
                $data[] = "Micropore";
                $data[] = "Seringa 10ml";
                $data[] = "Seringa 20ml";
                $data[] = "Seringa 5ml";
                $data[] = "Torneira 3 vias";
                $data[] = "Lidocaína 2% s/vaso";
                $data[] = "Lidocaína 2% c/vaso";
                $data[] = "Neocaína 2% s/vasco";
                $data[] = "Neocaína 2% sc/vasco";
                $data[] = "Xilocaina em Gel";
                $data[] = "Tenoxican";
                $data[] = "Polidocanol";
                $data[] = "Epnefrina";
                $data[] = "Glicose 50%";
                $data[] = "Introdutor";
                $data[] = "Glicose 75%";
                $data[] = "Clorexidina Alcoolica";
                $data[] = "Scalp";
                $data[] = "Cateter Uretal nº6";
                $data[] = "Cateter Uretal nº8";
                break;
            case 19 :
            case 45 :

                switch ($sub_especialidade) {
                    case 1 :
                        $data[] = "Agulha 13 x 4,5";
                        $data[] = "Agulha 30x7";
                        $data[] = "Agulha 40x12";
                        $data[] = "Avental descartável";
                        $data[] = "Cateter oxigênio";
                        $data[] = "Clorexidina alcoolico";
                        $data[] = "Clorexidina aquoso";
                        $data[] = "Eletrodo descartavel";
                        $data[] = "Equipo macrogotas";
                        $data[] = "Escova Clorex";
                        $data[] = "Fio Prolene nº 2,0";
                        $data[] = "Gaze esteril";
                        $data[] = "Cotonete";
                        $data[] = "Jelco nº";
                        $data[] = "Lâmina bisturi nº 11";
                        $data[] = "Lâmina bisturi nº 15";
                        $data[] = "Lâmina bisturi nº 22";
                        $data[] = "Luva cirurgica nº 6,5";
                        $data[] = "Luva cirurgica nº 7";
                        $data[] = "Luva cirurgica nº 7,5";
                        $data[] = "Luva cirurgica nº8";
                        $data[] = "LUVA ESTÉRIL SEM PÓ 6,5 (ESPECIAL)";
                        $data[] = "LUVA ESTÉRIL SEM PÓ 7,5 (ESPECIAL)";
                        $data[] = "Micropore";
                        $data[] = "Selante de  fibrina";
                        $data[] = "PVPI tópico";
                        $data[] = "Seringa 10ml";
                        $data[] = "Seringa 1ml";
                        $data[] = "Seringa 20ml";
                        $data[] = "Seringa 5ml";
                        $data[] = "Xilocaína s/vaso";
                        $data[] = "Fio de Nylon nº10";
                        $data[] = "Xilocaína c/vaso";
                        $data[] = "Anestalcon";
                        $data[] = "Tropicamida";
                        $data[] = "CICLOPLEGICO 1% COL. 5 ML SOL. ";
                        $data[] = "CIPROFLOXACINO+DEXAMETASONA ";
                        $data[] = "PVPI COLIRIO 5% C/ 5 ML";
                        $data[] = "Compressa";
                        break;
                    case 2 :
                        $data[] = "Agulha 13 x 4,5";
                        $data[] = "Agulha 30x7";
                        $data[] = "Agulha 40x12";
                        $data[] = "Avental descartável";
                        $data[] = "Cateter oxigênio";
                        $data[] = "Clorexidina alcoolico";
                        $data[] = "Clorexidina aquoso";
                        $data[] = "Eletrodo descartavel";
                        $data[] = "Equipo macrogotas";
                        $data[] = "Fio Catgut simples nº4,0";
                        $data[] = "Fio Prolene nº 2,0";
                        $data[] = "Frasco anatomo";
                        $data[] = "Gaze 7,5 x 7,5";
                        $data[] = "Jelco nº";
                        $data[] = "Lâmina bisturi nº 11";
                        $data[] = "Lâmina bisturi nº 15";
                        $data[] = "Lamina bisturi nº 22";
                        $data[] = "Luva cirurgica nº 6,5";
                        $data[] = "Luva cirurgica nº 7";
                        $data[] = "Luva cirurgica nº 7,5";
                        $data[] = "Luva cirurgica nº8";
                        $data[] = "LUVA ESTÉRIL SEM PÓ 6,5 (ESPECIAL)";
                        $data[] = "LUVA ESTÉRIL SEM PÓ 7,5 (ESPECIAL)";
                        $data[] = "Micropore";
                        $data[] = "Selante de  fibrina";
                        $data[] = "PVPI tópico";
                        $data[] = "Seringa 10ml";
                        $data[] = "Seringa 1ml";
                        $data[] = "Seringa 20ml";
                        $data[] = "Seringa 5ml";
                        $data[] = "Xilocaína s/vaso";
                        $data[] = "Fio de Nylon nº10";
                        $data[] = "Xilocaína c/vaso";
                        $data[] = "Anestalcon";
                        $data[] = "Tropicamida";
                        $data[] = "CICLOPLEGICO 1% COL. 5 ML SOL.";
                        $data[] = "CIPROFLOXACINO+DEXAMETASONA ";
                        $data[] = "PVPI COLIRIO 5% C/ 5 ML";
                        break;

                }
                break;

            case 22 :

                switch ($sub_especialidade) {
                    case 4 :
                    case 5 :
                        $data[] = "Água destilada 10ml";
                        $data[] = "Agulha 13 x 4,5";
                        $data[] = "Agulha 30x7";
                        $data[] = "Agulha 40x12";
                        $data[] = "Avental descartável";
                        $data[] = "Cateter oxigênio";
                        $data[] = "Clorexidina alcoolico";
                        $data[] = "Clorexidina aquoso";
                        $data[] = "Coban ____ cm";
                        $data[] = "Cefalotina";
                        $data[] = "Dipirona";
                        $data[] = "Diazepan";
                        $data[] = "Eletrodo descartavel";
                        $data[] = "Equipo macrogotas";
                        $data[] = "Esparadrapo";
                        $data[] = "Fio Catgut simples nº4,0";
                        $data[] = "Fio Prolene nº 2,0";
                        $data[] = "Fio nylon 3.0";
                        $data[] = "Fio nylon 4.0";
                        $data[] = "Fio nylon 5.0";
                        $data[] = "Fio nylon 6.0";
                        $data[] = "Frasco anatomo";
                        $data[] = "Jelco nº";
                        $data[] = "Lâmina bisturi nº 11";
                        $data[] = "Lâmina bisturi nº 15";
                        $data[] = "Lâmina bisturi nº 22";
                        $data[] = "Luva cirurgica nº 6,5";
                        $data[] = "Luva cirurgica nº 7";
                        $data[] = "Luva cirurgica nº 7,5";
                        $data[] = "Luva cirurgica nº8";
                        $data[] = "Micropore";
                        $data[] = "Nebacetin";
                        $data[] = "Neocaina s/vaso";
                        $data[] = "Seringa 1ml";
                        $data[] = "Seringa 10ml";
                        $data[] = "Seringa 5ml";
                        $data[] = "Seringa 20ml";
                        $data[] = "Texoxican";
                        $data[] = "Torneira 3 vias";
                        $data[] = "Lidocaína 2% s/vaso";
                        $data[] = "Lidocaína 2% c/vaso";
                        $data[] = "Neocaína 2% s/vasco";
                        break;
                }
                break;
            case 47 :
            case 46 :
                $data[] = "Água destilada 10ml";
                $data[] = "Agulha 13 x 4,5";
                $data[] = "Agulha 30x7";
                $data[] = "Agulha 40x12";
                $data[] = "Avental descartável";
                $data[] = "Cateter oxigênio";
                $data[] = "Clorexidina alcoolico";
                $data[] = "Clorexidina aquoso";
                $data[] = "Coban ____ cm";
                $data[] = "Cefalotina";
                $data[] = "Dipirona";
                $data[] = "Diazepan";
                $data[] = "Eletrodo descartavel";
                $data[] = "Equipo macrogotas";
                $data[] = "Esparadrapo";
                $data[] = "Fio Catgut simples nº4,0";
                $data[] = "Fio Prolene nº 2,0";
                $data[] = "Fio nylon 3.0";
                $data[] = "Fio nylon 4.0";
                $data[] = "Fio nylon 5.0";
                $data[] = "Fio nylon 6.0";
                $data[] = "Frasco anatomo";
                $data[] = "Jelco nº";
                $data[] = "Lâmina bisturi nº 11";
                $data[] = "Lâmina bisturi nº 15";
                $data[] = "Lâmina bisturi nº 22";
                $data[] = "Luva cirurgica nº 6,5";
                $data[] = "Luva cirurgica nº 7";
                $data[] = "Luva cirurgica nº 7,5";
                $data[] = "Luva cirurgica nº8";
                $data[] = "Micropore";
                $data[] = "Nebacetin";
                $data[] = "Neocaina s/vaso";
                $data[] = "Seringa 1ml";
                $data[] = "Seringa 10ml";
                $data[] = "Seringa 5ml";
                $data[] = "Seringa 20ml";
                $data[] = "Texoxican";
                $data[] = "Torneira 3 vias";
                $data[] = "Lidocaína 2% s/vaso";
                $data[] = "Lidocaína 2% c/vaso";
                $data[] = "Neocaína 2% s/vasco";
                break;
        }

        asort($data);

        return $data;
    }

    public static function getFolhaDebitoCentralMaterial($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 49 :
            case 9 :
                $data[] = "Avental cirurgião";
                $data[] = "Atadura";
                $data[] = "Algodão Ortopedico";
                $data[] = "Caixa de P.C.";
                $data[] = "Campo cirúrgico (   )";
                $data[] = "Campo fenestrado (   )";
                $data[] = "Compressa c/ 5";
                $data[] = "Capa para videocirurgia envelopada";
                $data[] = "Gaze esteril";
                $data[] = "Malha Tubular ";
                $data[] = "Ponta Aspirador";
                $data[] = "Ponta Bisturi";
                $data[] = "Fibra endolaser";
                $data[] = "Escova de Clorexidine";
                break;
            case 22 :
            case 47 :
            case 46 :
                $data[] = "Avental cirurgião";
                $data[] = "Caixa de P.C.";
                $data[] = "Campo cirúrgico (   )";
                $data[] = "Campo fenestrado  (   )";
                $data[] = "Compressa ";
                $data[] = "Gaze esteril";
                $data[] = "Ponta Aspirador";
                $data[] = "Ponta Bisturi";
                break;
            case 19 :
            case 45 :
                switch ($sub_especialidade) {
                    case 1 :
                        $data[] = "Avental cirurgião";
                        $data[] = "Caixa de P.C.";
                        $data[] = "Campo cirúrgico (   )";
                        $data[] = "Campo fenestrado (   )";
                        $data[] = "Ponta Aspirador";
                        $data[] = "Ponta Bisturi";
                        $data[] = "Caneta eletrocautério";
                        $data[] = "(BSS) SOLUCÃO SALINA BALANCEADA FR 250 ML ";
                        $data[] = "CAMPO CIRURGICO FENESTRADO 8 CM TAM 1 X 1,20 C/ BAG";
                        $data[] = "CEFUROXIMA 750 MG FAM ";
                        $data[] = "CLORETO DE CARBACOL 0,2 MG/2 ML FAM 2ML";
                        $data[] = "HIDROXIPROPIL METILCELULOSE 2% SERINGA 1,5ML";
                        $data[] = "HIDROXIPROPIL METILCELULOSE 4% SERINGA 1,5ML";
                        $data[] = "BISTURI C/ LAMINA 15 G OFTALMO";
                        $data[] = "BISTURI C/ LAMINA 2,75 MM OFTALMO";
                        $data[] = "LENTE";
                        $data[] = "TRYPAN 0,1% FAM 1 ML";
                        $data[] = "INJETOR";
                        $data[] = "CARTUCHO";
                        $data[] = "TAMPÃO ACRÍLICO";
                        $data[] = "VIGADEXA";
                        $data[] = "VIGAMOX";
                        $data[] = "TEROLAC";
                        break;
                    case 2 :
                        $data[] = "Avental cirurgião";
                        $data[] = "Caixa de P.C.";
                        $data[] = "Campo cirúrgico (   )";
                        $data[] = "Campo fenestrado (   )";
                        $data[] = "Compressa";
                        $data[] = "Gaze esteril";
                        $data[] = "Ponta Aspirador";
                        $data[] = "Ponta Bisturi";
                        $data[] = "Escova Clorex";
                        $data[] = "Cotonete";
                        $data[] = "Caneta eletrocautério";
                        break;
                }
                break;
        }

        asort($data);

        return $data;
    }

    public static function getFolhaDebitoEquipamento($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 9 :
            case 46 :
            case 47 :
            case 49 :
            case 22 :
            case 19 :
            case 45 :
                $data[] = "Aspirador";
                $data[] = "Bisturi eletrico";
                $data[] = "Monitor cardíaco";
                $data[] = "Oxigenio L/min";
                $data[] = "Oximetro";
                break;
        }

        asort($data);

        return $data;
    }


    public static function getFolhaDebitoSoro($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 9 :
            case 49 :
                $data[] = "Fisiologico 250ml";
                $data[] = "Fisiologico 500ml";
                $data[] = "Glicosado 5% 500ml";
                break;
            case 47 :
            case 22 :
            case 19 :
            case 45 :
            case 46 :
                $data[] = "Fisiologico 250ml";
                $data[] = "Fisiologico 500ml";
                $data[] = "Glicosado 5% 500ml";
                $data[] = "Manitol 250ml";
                $data[] = "Ringer Lactato";
                $data[] = "Ringer Simples";
                break;
        }

        asort($data);

        return $data;
    }


}