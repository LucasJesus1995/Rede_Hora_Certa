<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 14/07/15
 * Time: 19:43
 */

namespace App\Http\Helpers;


use App\Agendas;
use App\AnamnesePerguntas;
use App\Condutas;

class AtendimentoHelpers
{
    public static function getEscalaDor()
    {
        $data[0] = 'Sem dor';
        $data[2] = 'Leve';
        $data[4] = 'Moderada';
        $data[6] = 'Severa';
        $data[8] = 'Muito servera';
        $data[10] = 'Insuportável';

        return $data;
    }

    public static function getTipoAnexoAtendimento()
    {
        $data[1] = 'AIH';
        $data[2] = 'APAC';
        $data[3] = 'Descrição cirúrgica';

        return $data;
    }

    public static function getCondutasEspecialidadeTipoAtendimento($especialidade, $tipo_atendimento, $regulacao = 0)
    {
        return Condutas::ComboEspecialidadeTipoAtendimento($especialidade, $tipo_atendimento, $regulacao);
    }

    public static function getCondutas($key = null)
    {
        $data = Condutas::Combo();

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getLateralidades($key = null)
    {
        $data['OD'] = 'Direito';
        $data['OE'] = 'Esquerdo';
        $data['AMBOS'] = 'Ambos';

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getKitsAvulso($key = null)
    {
        $data[1] = 'Escleroterapia de varizes dos membros inferiores';
        $data[2] = 'Termo de consentimento Vasectomia';
        $data[3] = 'Mapeamento de retina';
        $data[4] = 'Termo de consentimento Yag Laser';
        $data[5] = 'Termo de consentimento e Responsabilidade';

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function validaProcedimentoAPACByAgenda($agenda)
    {
        $data = Agendas::select(['procedimentos.id', 'atendimento_procedimentos.autorizacao'])->where('agendas.id', $agenda)
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('procedimentos.obrigar_preenchimento_apac', 1)
            ->where(function ($q) {
                $q->where('atendimento_procedimentos.autorizacao', '=', '')
                    ->orWhere('atendimento_procedimentos.autorizacao', '=', null);
            })
            ->get()->count();

        return $data == 0;
    }

} 