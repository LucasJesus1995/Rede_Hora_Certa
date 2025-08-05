<?php

namespace App\Http\Helpers\Cirurgico;


class KitImpressaoHelpers
{

    public static function getProcedimentos($linha_cuidado, $subespecialidade)
    {
        $data = [];

        switch ($linha_cuidado) {
            case "49" :
                $data[] = "252";
                $data[] = "253";
                $data[] = "271";
                $data[] = "272";
                $data[] = "216";
                break;
            case "9" :
                $data[] = "272";
                break;
            case "37" :
                $data[] = "222";
                $data[] = "221";
                $data[] = "224";
                $data[] = "223";
                $data[] = "4";
                break;
            case "47" :
                $data[] = "264";
                break;
            case "32" :
                $data[] = "261";
                $data[] = "262";
                $data[] = "270";
                $data[] = "260";
                $data[] = "263";
                $data[] = "222";
                $data[] = "223";
                $data[] = "4";
                break;
            case "19" :
            case "45" :
                switch ($subespecialidade) {
                    case 1 :
                        $data[] = "227";
                        break;
                    case 2 :
                        $data[] = "225";
                        break;
                }
                break;
            case "22" :
                switch ($subespecialidade) {
                    case 4 :
                        $data[] = "258";
                        $data[] = "305";
                        $data[] = "306";
                        $data[] = "259";
                        $data[] = "307";
//                        $data[] = "221";
//                        $data[] = "222";
//                        $data[] = "223";
//                        $data[] = "4";
                        break;
                    case 5 :
                        $data[] = "221";
                        $data[] = "224";
                        $data[] = "223";
                        $data[] = "4";
                        $data[] = "160";
                        $data[] = "222";
                        break;
                }
                break;
            case 46 :
                $data[] = "221";
                $data[] = "224";
                $data[] = "223";
                $data[] = "4";
                $data[] = "160";
                $data[] = "222";
                break;
        }

        asort($data);

        return $data;
    }

    public static function getSubEspecialidades($linha_cuidado)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 45 :
                $data[1] = "Catarata";
                $data[2] = "Pterígio";
                $data[3] = "Yag Laser";
                break;
            case 22 :
                $data[4] = "Hérnia";
                $data[5] = "Pequena cirurgia";
                break;
            case 44 :
                $data[8] = "Hemorroidectomia";
                $data[9] = "Fistulectomia";
                break;
        }

        asort($data);

        return $data;
    }

    public static function getSubEspecialidadesLinhaCuidado()
    {
        return [45, 22, 24];
    }

    public static function getReceita($linha_cuidado, $subespecialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case 47 :
                $data[""] = "USO ORAL";
                $data["1) Diclofenaco Sódico 50 mg"] = [
                    'Tomar 1 cp de 8 em 8 horas por 5 dias',
                ];
                $data["2) Tylenol gotas"] = [
                    'Tomar 40  gotas de 6 em 6 horas por 5 dias e, após, se tiver dor.',
                ];
                $data["3) Cefalexina 500 mg"] = [
                    'Tomar 1 cp de 6 em 6 horas por 7 dias',
                ];
                break;
            case 19 :
            case 45 :
                switch ($subespecialidade) {
                    case 1 :
                        $data[""] = "USO OCULAR (NO OLHO OPERADO)";
                        $data["1) Vigadexa (ou Zypred) colírio __________________________________ 02 frascos"] = [
//                            'Pingar 01 GOTA DE 2/2 horas no dia da cirurgia e no primeiro dia depois da cirurgia e após pingar 01 gota de 4/4 H por 9 dias e parar.',
                            'Uso: 01 gota em olho operado:
                            <br />&nbsp;&nbsp;&nbsp; 1/1 hora no dia da cirurgia
                            <br />&nbsp;&nbsp;&nbsp; 2/2 horas por 07 dias
                            <br />&nbsp;&nbsp;&nbsp; 4/4 horas por 07 dias
                            <br />&nbsp;&nbsp;&nbsp; 6/6 horas por 07 dias
                            <br />&nbsp;&nbsp;&nbsp; 12/12 horas por 07 dias
                            '
                        ];
                        $data["<br/><br/>2) Terolac ou Cetrolac __________________________________________ 01 frasco"] = [
                            'Uso: 01 gota em olho operado de 6/6 horas por 30 dias',
                        ];
                        break;
                    case 2 :
                        $data["0"] = "USO OCULAR";
                        $data["1) MAXIFLOX D ou CYLOCORT __________________________________ 02 frascos"] = [
                            'Pingar 01 gota no olho operado:',
                            '4/4h por 5 dias',
                            '6/6h por 5 dias',
                            '8/8h por 5 dias',
                            '12/12h por 5 dias',
                            '1 vez ao dia por 5 dias',
                        ];
                        $data["2) ECOFILM ou LACRIFILM __________________________________ 02 frascos"] = ["Pingar uma gota no olho operado de 6/6h por 30 dias"];

                        $data["2"] = "USO ORAL";
                        $data["3) Dipirona 500MG (Comprimido)"] = ["Tomar de 6/6h por 3 dias"];

                        break;
                }
                break;
            case 22 :
                switch ($subespecialidade) {
                    case 4 :
                        $data[""] = "USO Oral:";
                        $data["1) Dipirona gotas ___________________ 01 frasco"] = [
                            'Tomar 40 gotas via oral, a cada 6 horas se dor',
                        ];
                        $data["2) Diclofenaco sódico 50mg ___________ 09 cps"] = [
                            'Tomar 01 cp via oral, a cada 8 horas por 3 dias',
                        ];
                        $data["3) Cefalexina 500mg______________ 28 cps"] = [
                            'Tomar 01 cp via oral, a cada 6 horas por 7 dias',
                        ];
                        $data["Orientações para pós-operatório:"] = [
                            '1. Lavar a ferida operatória com água e sabonete',
                            '2. Colocar curativo se houver saída de secreção',
                        ];
                        break;
                    case 5 :
                        $data[""] = "USO Oral:";
                        $data["CEFALEXINA 500mg _____________________________________ 28 cp"] = [
                            '01 cp. VO 6/6 horas por 7 dias',
                        ];
                        break;

                }
                break;
            case 9 :
                $data[""] = "USO Oral:";
                $data["1) Dipirona gotas ___________________ 01 frasco"] = [
                    'Tomar 40 gotas via oral, a cada 6 horas se dor',
                ];
                $data["2) Diclofenaco sódico 50mg ___________ 09 cps"] = [
                    'Tomar 01 cp via oral, a cada 8 horas por 3 dias',
                ];
                break;
            case 46 :
                $data[""] = "USO Oral:";
                $data["CEFALEXINA 500mg _____________________________________ 28 cp"] = [
                    '01 cp. VO 6/6 horas por 7 dias',
                ];
                break;
        }

        return $data;
    }


}