<?php


namespace App\Http\Rules\Faturamento;


use App\ContratoProcedimentos;
use Illuminate\Support\Facades\DB;

class Procedimentos
{

    public static function getProcedimentosFaturados()
    {
        return array_merge(self::getProcedimentosCirurgicos(), self::getProcedimentosDiagnostico(), self::getSisMama(), self::getAIH());
    }

    public static function getProcedimentosCirurgicos()
    {
        return [156, 227, 247, 301];
    }


    public static function getSisMama()
    {
        return [295];
    }

    public static function getAIH()
    {
        return [251, 252, 253, 258, 259, 264, 265, 269];
    }


    public static function getProcedimentosDiagnostico()
    {
        return [
            1,
            2,
            3,
            4,
            5,
            6,
            11,
            12,
            13,
            14,
            17,
            25,
            30,
            36,
            37,
            38,
            40,
            41,
            42,
            43,
            44,
            45,
            46,
            47,
            48,
            49,
            50,
            51,
            52,
            53,
            78,
            79,
            84,
            85,
            86,
            87,
            88,
            89,
            90,
            91,
            92,
            93,
            94,
            95,
            96,
            97,
            98,
            99,
            100,
            101,
            102,
            103,
            104,
            105,
            106,
            107,
            108,
            122,
            123,
            124,
            125,
            126,
            127,
            128,
            129,
            130,
            131,
            132,
            133,
            134,
            135,
            136,
            140,
            142,
            143,
            145,
            146,
            151,
            152,
            153,
            154,
            155,
            157,
            158,
            159,
            161,
            162,
            163,
            220,
            221,
            222,
            223,
            224,
            225,
            226,
            219,
            226,
            248,
            271,
            272,
            302,
            313,
            314,
            317,
            318,
            319,
            320,
            321,
            322
        ];
    }

    public static function getMultiplicadorMedicos($procedimento)
    {
        switch ($procedimento) {
            case 91 : // ULTRASSONOGRAFIA DOPPLER COLORIDO DE MMSS ARTERIAL E VENOSO
            case 87 : // ULTRASSONOGRAFIA DOPPLER COLORIDO DE MMSS ARTERIAL OU VENOSO
            case 92 : // ULTRASSONOGRAFIA DOPPLER COLORIDO DE MMII ARTERIAL E VENOSO
            case 88 : // ULTRASSONOGRAFIA DOPPLER COLORIDO DE MMII ARTERIAL OU VENOSO
            case 84 : // ELETRONEUROMIOGRAMA (ENMG) - (MMSS) 2 SEG
            case 36 : // ELETRONEUROMIOGRAMA (ENMG) - (FACE, BOCA OU 1 MEMBRO) 2 SEG
            case 37 : // ELETRONEUROMIOGRAMA (ENMG) - (MMII) 2 SEG
                $multiplicador = 2;
                break;
            case 38 : // ELETRONEUROMIOGRAMA (ENMG) - (MMII E MMSS) 4 SEG
                $multiplicador = 4;
                break;
            default :
                $multiplicador = 1;
                break;
        }

        return intval($multiplicador);
    }

    public static function getProcedimentosContratoByLote($lote)
    {
        $procedimentos_demanda = ContratoProcedimentos::select(
            [
                'procedimentos.sus',
                DB::RAW('SUM(contrato_procedimentos.demanda) AS demanda'),
            ]
        )
            ->join('procedimentos', 'procedimentos.id', '=', 'contrato_procedimentos.procedimento')
            ->where('demanda', '>', 0)
            ->where('procedimentos.ativo', 1)
            ->where('lote', $lote)
            ->where('procedimentos.sus', '!=', "")
//            ->where('procedimentos.sus', '0405050020')
            ->groupBy('procedimentos.sus')
            ->orderBy('procedimentos.sus', 'asc')
            ->get();

        return $procedimentos_demanda;
    }

}