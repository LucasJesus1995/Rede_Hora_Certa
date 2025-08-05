<?php

namespace App\Http\Helpers\Importacao;

use App\Agendas;
use App\Arenas;
use App\Http\Helpers\Util;
use App\ImportacaoAgenda;
use App\LinhaCuidado;

class ImportacaoAgendasHelpers
{
    public function getAgendamentos($day, $arena = null){
        $data = Util::Date2DB($day);
        $date['start'] = $data." 00:00:00";
        $date['end'] = $data." 23:59:59";

        $importacao_agendas = ImportacaoAgenda::select(
            [
                'users.name AS usuario',
                'users.email AS email',
                'importacao_agenda.*',

            ]
        )
            ->join('users', 'users.id', '=', 'importacao_agenda.user')
            ->whereRaw("DATE(importacao_agenda.created_at) = '{$data}'")
//            ->limit(10)
            ->orderBy('id', 'desc')->get();

        $data = null;
        if (!empty($importacao_agendas)) {
            $_arenas = Arenas::ComboContrato();

            $_especialidades = LinhaCuidado::Combo();
            $_classificacao = Util::getTipoAtendimento();

            foreach ($importacao_agendas as $row) {
                $_data = !empty($row->data) && Util::isSerialized($row->data) ? unserialize($row->data) : null;
                if (!empty($arena) && !empty($_data['arena'])) {
                    if ($arena != $_data['arena']) {
                        continue;
                    }
                }

                if (!empty($_data) && !empty($_data['arena'])) {
                    $data_agendamento = $_data['data'];
                    if (is_array($_data['data'])) {
                        $agenda = Agendas::select('data')->where('import', 'LIKE', "{$row->id}-%")->orderBy('id', 'desc')->limit(1)->get();

                        if (!empty($agenda[0])) {
                            $data_agendamento = Util::DBTimestamp2UserDate($agenda[0]->data);
                        }
                    }

                    $data[$row->id]['id'] = $row->id;
                    $data[$row->id]['data_importacao'] = Util::DBTimestamp2User2($row->created_at);
                    $data[$row->id]['data'] = is_array($data_agendamento) ? null : $data_agendamento;
                    $data[$row->id]['user'] = strtoupper($row->usuario) . "<" . strtolower($row->email) . ">";
                    $data[$row->id]['tipo'] = ($row->tipo == 3) ? "PDF" : "EXCEL";
                    $data[$row->id]['tipo_atendimento'] = !empty($_data['tipo_atendimento']) && array_key_exists($_data['tipo_atendimento'], $_classificacao) ? $_classificacao[$_data['tipo_atendimento']] : null;
                    $data[$row->id]['registro'] = $row->records;
                    $data[$row->id]['importacao'] = $row->imported;
                    $data[$row->id]['falhas'] = $row->failure;
                    $data[$row->id]['arena'] = !empty($_data['arena']) && array_key_exists($_data['arena'], $_arenas) ? $_arenas[$_data['arena']] : null;
                    $data[$row->id]['linha_cuidado'] = !empty($_data['arena']) && array_key_exists($_data['linha_cuidado'], $_especialidades) ? $_especialidades[$_data['linha_cuidado']] : null;

                    unset($_data);
                }

            }
        }

        return $data;
    }

}