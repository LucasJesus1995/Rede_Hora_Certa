<?php

namespace App\Http\Helpers\Exportacao;


use App\Arenas;
use App\Atendimentos;
use App\CEPs;
use App\Http\Helpers\Util;
use App\Http\Rules\Faturamento\Procedimentos;
use Carbon\Carbon;

class APACProducao extends ExportacaoProducao
{

    private $contrato;
    private $mes;
    private $ano;
    private $lote;
    private $rows;
    private $competencia;
    private $limite_competencia;
    private $faturamento;
    private $autorizador;
    private $headers;
    private $controle;
    private $total_apac = 0;

    public function Exportacao($params)
    {
        $this->rows = [];
        $this->controle = null;

        $this->autorizador['nome'] = "YARA DA FONSECA SELLARO";
        $this->autorizador['cns'] = "980016288239084";

        $this->ano = $params['ano'];
        $this->mes = $params['mes'];
        $this->faturamento = $params['faturamento'];

        $this->contrato = $this->getContrato($params['lote']);
        $this->lote = $this->getLote($params['lote']);

        $this->limite_competencia = Carbon::create($this->ano, $this->mes, 1, 0, 0, 0);
        $this->competencia = Carbon::create($this->ano, $this->mes, 1, 0, 0, 0);
        $this->competencia_ano_mes = $this->competencia->format("Ym");

        $this->getHeaders();
        $this->getProcedimentosRows();

        $this->headers[5] = Util::StrPadRight($this->getControle(), 4, 0);
        $this->headers[4] = Util::StrPadLeft($this->total_apac, 6, 0);
        array_unshift($this->rows, implode("",$this->headers));

        return implode("\r\n", $this->rows);
    }

    private function getHeaders()
    {
        $this->headers = null;
        $this->headers[1] = Util::StrPadRight("01", 2, " ");
        $this->headers[2] = Util::StrPadRight("#APAC", 5, " ");
        $this->headers[3] = $this->competencia_ano_mes;
        $this->headers[4] = null;
        $this->headers[5] = null;
        $this->headers[6] = Util::StrPadRight("HORA CERTA CIES", 30, " ");
        $this->headers[7] = Util::StrPadRight("HCC", 6, " ");
        $this->headers[8] = Util::StrPadLeft("06950310000153", 14, "0");
        $this->headers[9] = Util::StrPadRight("SECRETARIA MUNICIPAL DE SAUDE", 40, " ");
        $this->headers[10] = Util::StrPadRight("M", 1, " ");
        $this->headers[11] = date("Ymd");
        $this->headers[12] = Util::StrPadRight("Versao 02.35", 15, " ");
        $this->headers[13] = Util::StrPadRight("", 2, " ");

    }

    private function getProcedimentosRows()
    {
        $atendimentos = $this->getAtendimentos();

        $lines = null;
        if (!empty($atendimentos[0])) {
            foreach ($atendimentos AS $row) {
                //$agendamento = Util::DateObject($row->agendamento);
                $agendamento = Carbon::createFromDate($this->ano, $this->mes, "01");

                $data_ocorrencia = $agendamento->format("Ymd");

                $nascimento = Util::DateObject($row->nascimento);

                $codigo_procedimento = $this->getProcedimentoPrincipal($row->atendimento);

                $data_solicitacao = "{$this->ano}{$this->mes}01";
                $data_autorizacao = "{$this->ano}{$this->mes}02";

                $data_validade = Carbon::createFromDate($agendamento->format('Y'), $agendamento->format('m'), "01")->addMonth(2)->endOfMonth()->format("Ymd");

                $line[1] = "14";
                $line[2] = $this->competencia_ano_mes;
                $line[3] = Util::StrPadRight($row->autorizacao, 13, " ");
                $line[4] = Util::StrPadRight(35, 2, " ");
                //$line[5] = Util::StrPadRight($row->arena_cnes, 7, " ");
                $line[5] = Util::StrPadRight("7385978", 7, " ");
                $line[6] = $agendamento->format("Ymd");
                $line[7] = $agendamento->format("Ym01");
                $line[8] = $data_validade;
                $line[9] = Util::StrPadRight(null, 2, "0");
                $line[10] = Util::StrPadRight(3, 1, "0");
                $line[11] = Util::StrPadRight($row->nome, 30, " ");
                $line[12] = Util::StrPadRight($row->mae, 30, " ");
                $line[13] = Util::StrPadRight($this->getEnderecoTipoDescricao($row->endereco_tipo) . " " . $row->endereco, 30, " ");
                $line[14] = Util::StrPadRight($row->numero, 5, " ");
                $line[15] = Util::StrPadRight($row->complemento, 10, " ");
                $line[16] = Util::StrPadLeft(CEPs::ValidaCEPExportacao($row->cep), 8);
                $line[17] = Util::StrPadRight($this->getIbge($row->cidade), 7, " ");
                $line[18] = $nascimento->format("Ymd");
                $line[19] = ($row->sexo == 1) ? "M" : "F";
                //$line[20] = Util::StrPadRight($this->getMedico($row->medico), 30, " ");
                $line[20] = Util::StrPadRight("FLAVIO GAIETA HOLZCHUH", 30, " ");
                $line[21] = Util::StrPadRight($codigo_procedimento, 10, " ");
                $line[22] = Util::StrPadRight("18", 2, " ");
                $line[23] = $data_ocorrencia;
                $line[24] = Util::StrPadRight($this->autorizador['nome'], 30, " ");
                $line[25] = Util::StrPadLeft($row->pacientes_cns, 15, " ");
                //$line[26] = Util::StrPadLeft($this->getMedicoCNS($row->medico), 15, " ");
                $line[26] = Util::StrPadLeft("980016280730846", 15, " ");
                $line[27] = Util::StrPadLeft($this->autorizador['cns'], 15, " ");
                $line[28] = Util::StrPadRight(null, 4, " ");
                //$line[29] = Util::StrPadLeft($row->atendimento, 10, 0);
                $line[29] = Util::StrPadLeft(null, 10, 0);
                $line[30] = Util::StrPadLeft(null, 7, 0);
                $line[31] = $data_solicitacao;
                $line[32] = $data_autorizacao;
                $line[33] = Util::StrPadRight("M355030001", 10, " ");
                $line[34] = Util::StrPadRight("01", 2, " ");
                $line[35] = Util::StrPadLeft(null, 13, 0);
                $line[36] = Util::StrPadRight("99", 2, " ");
                $line[37] = Util::StrPadRight($row->mae, 30, " ");
                $line[38] = Util::StrPadRight("010", 3, " ");
                $line[39] = Util::StrPadRight(null, 4, " ");
                $line[40] = Util::StrPadRight("081", 3, " ");
                $line[41] = Util::StrPadRight($row->bairro, 30, " ");
                $line[42] = Util::StrPadRight(null, 2, 0);
                $line[43] = Util::StrPadRight(null, 9, 0);
                $line[44] = Util::StrPadLeft(null, 40, " ");
                $line[45] = Util::StrPadRight("980016280730846", 15, " ");
                $line[46] = Util::StrPadLeft(null, 10, " ");
                $line[47] = Util::StrPadRight(null, 2, " ");

                $this->controle += ($codigo_procedimento + $row->quantidade + $row->autorizacao);

                $line_registro_procedimento = $this->getRegistroProcedimentoRow($row);
                $line_variavel_procedimento = $this->getVariavelProcedimentoRow($row);

                $this->rows[] = implode("", $line);
                $this->rows[] = implode("", $line_registro_procedimento);
                $this->rows[] = implode("", $line_variavel_procedimento);

                $this->total_apac++;
            }
        }
    }

    private function getVariavelProcedimentoRow($row){
        $line[1] = Util::StrPadRight("06", 2, " ");
        $line[2] = $this->competencia_ano_mes;
        $line[3] = Util::StrPadRight($row->autorizacao, 13, " ");
        $line[4] = Util::StrPadRight($this->getCodigoCID($row->cid_primario), 4, " ");
        $line[5] = Util::StrPadRight($this->getCodigoCID($row->cid_secundario), 4, " ");
        $line[6] = Util::StrPadRight(null, 2, " ");

        return $line;
    }

    private function getRegistroProcedimentoRow($row)
    {
        $line[1] = Util::StrPadRight("13", 2, " ");
        $line[2] = $this->competencia_ano_mes;
        $line[3] = Util::StrPadRight($row->autorizacao, 13, " ");
        $line[4] = Util::StrPadRight($row->sus, 10, " ");
        $line[5] = Util::StrPadRight($row->cbo, 6, " ");
        $line[6] = Util::StrPadLeft($row->quantidade, 7, 0);
        $line[7] = Util::StrPadRight(null, 14, " ");
        $line[8] = Util::StrPadRight(null, 6, " ");
        $line[9] = Util::StrPadRight($this->getCodigoCID($row->cid_primario), 4, " ");
        $line[10] = Util::StrPadRight($this->getCodigoCID($row->cid_secundario), 4, " ");
        $line[11] = Util::StrPadLeft($row->servico_bpa, 3, 0);
        $line[12] = Util::StrPadLeft($row->class_bpa, 3, 0);
        $line[13] = Util::StrPadRight(null, 8, " ");
        $line[14] = Util::StrPadRight(null, 4, " ");
        $line[15] = Util::StrPadRight(null, 2, " ");

        return $line;
    }

    private function getAtendimentos()
    {
        $arenas = Arenas::getByLote($this->lote->id);

        $data = Atendimentos::select(
            [
                'atendimento.id AS atendimento',
                'agendas.id',
                'arenas.nome AS arena',
                'arenas.cnes AS arena_cnes',
                'agendas.data AS agendamento',
                'faturamento_procedimentos.quantidade',
                'pacientes.nascimento',
                'procedimentos.sus',
                'procedimentos.cid_primario',
                'procedimentos.cid_secundario',
                'profissionais.cns AS profissionais_cns',
                'procedimentos.cbo',
                'procedimentos.servico_bpa',
                'procedimentos.class_bpa',
                'pacientes.cns AS pacientes_cns',
                'pacientes.sexo',
                'pacientes.cidade',
                'pacientes.nome',
                'pacientes.mae',
                'pacientes.raca_cor',
                'pacientes.nacionalidade',
                'pacientes.cep',
                'pacientes.endereco_tipo',
                'pacientes.endereco',
                'pacientes.complemento',
                'pacientes.numero',
                'pacientes.bairro',
                'pacientes.celular',
                'pacientes.email',
                'atendimento_procedimentos.autorizacao',
                'atendimento.medico',
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('profissionais', 'atendimento_procedimentos.profissional', '=', 'profissionais.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('faturamento_procedimentos', function ($join) {
                $join->on('atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
                    ->where('faturamento_procedimentos.lote', '=', $this->lote->id)
                    ->where('faturamento_procedimentos.faturamento', '=', $this->faturamento)
                    ->where('faturamento_procedimentos.status', '=', 1);;
            })
            ->where('procedimentos.ativo', 1)
            ->whereIn('atendimento_procedimentos.procedimento', Procedimentos::getProcedimentosCirurgicos())
            ->whereIn('arenas.id', $arenas)
            ->orderBy('procedimentos.cbo', 'ASC')
            ->orderBy('profissionais.cns', 'ASC')
            ->orderBy('agendas.data', 'ASC')
            //->limit(33)
            ->get();
        
        return !empty($data[0]) ? $data : null;
    }

    protected function getControle(){
        return $this->headers[5];
    }


}