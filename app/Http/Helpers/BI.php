<?php


namespace App\Http\Helpers;

use App\Arenas;
use App\Atendimentos;
use App\CEPs;
use App\Cid;
use App\Cidades;
use App\Http\Rules\Faturamento\Procedimentos;
use App\Lotes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class BI
{
    private static $_cbos;
    private static $_cbos_next = [];
    private static $lote;
    protected static $limite_competencia;
    protected static $params;
    /**
     * @var int
     */
    private static $folhatotal;
    /**
     * @var int
     */
    private static $totallinhas;
    /**
     * @var string
     */
    private static $path;

    public static function BoletimProducaoAmbulatorial($params)
    {
        Artisan::call('cies:faturamento-procedimentos-consolidado');
        self::$params = $params;

        $uniquid = uniqid();
        self::$path = "uploads/tmp/bpa/" . $uniquid . "/";
        Upload::recursive_mkdir(self::$path);

        $_lote = Lotes::find($params['lote']);

        self::$lote = $_lote;
        self::$_cbos = Lotes::getProfissionaisCBO($params['lote']);

        BI::$limite_competencia = Carbon::create($params['ano'], $params['mes'], 1, 0, 0, 0);

        self::_Consolidado();
        self::_Individualizado();

        $command = "cd " . public_path(self::$path) . "../ ;zip -r {$uniquid}.zip {$uniquid}/*";

        exec($command);
        Upload::removerPath(self::$path);

        return str_replace("/.", ".", self::$path . ".zip");
    }

    private static function _Individualizado()
    {
        $params = self::$params;

        $headers = self::_BPAHeader($params);

        $blocks = self::_BPABocks2($params, 2);

        foreach ($blocks as $cbo => $rows) {
            self::$folhatotal = 1;
            self::$totallinhas = 1;

            $sequencial = 1;
            $folha = 1;
            if ($params['lote'] == 10) {
                $folha = 900;
            }

            $cbo_folha = null;
            $cnes_folha = null;
            $count = 1;

            $_data['01'] = null;
            $_data['03'] = null;

            foreach ($rows as $row) {
                $block = null;

                $nascimento = (!empty($row['nascimento']) && ($row['nascimento'] != '0000-00-00')) ? explode("-", current(explode(" ", $row['nascimento']))) : null;

                $cnes = self::getCNES($row['cbo'], $row['cns_original']);

                if (!$cnes) {
                    throw new \Exception("Falha ao gerar arquivo com CNES");
                    continue;
                } else {
                    //  $row['cns'] = $cnes;
                }

                $cidade = Cidades::find($row['cidade']);
                $cod_ibge = !empty($cidade->ibge) ? $cidade->ibge : '355030';

                $___data = explode(" ", $row['created_at']);
                $__data = explode("-", $___data[0]);

                $cid = null;
                if (!empty($row['cid'])) {
                    $_cid = Cid::get($row['cid']);
                    if (!empty($_cid->codigo)) {
                        $cid = $_cid->codigo;
                    }
                }

                $block['indentificacao'] = "03";
                $block['cnes'] = $headers['codigo'];
                $block['realizacao'] = $params['ano'] . $params['mes'];

                $block['cns'] = $row['cns']; // codigo nacional de saude
                $block['cbo'] = $row['cbo']; // classifica brasileira de ocupacao
                $block['atendimento'] = Carbon::createFromFormat('Y-m-d H:i:s', $row['created_at'])->format('Ymd');;
                $block['sequencial'] = $sequencial;
                $block['codigo-procedimento'] = $row['sus'];
                $block['pacientes_cns'] = $row['pacientes_cns'];
                $block['sexo'] = ($row['sexo'] == 1) ? 'M' : 'F';
                $block['cidade'] = $cod_ibge;
                $block['cid'] = $cid;
                $block['nascimento'] = $row['nascimento'];
                $block['quantidade'] = $row['quantidade'];
                $block['idade'] = ($nascimento) ? Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;
                $block['categoria'] = '01';
                $block['autorizacao-estabelecimento'] = $row['autorizacao'];
                $block['nome'] = $row['nome'];
                $block['nascimento'] = ($nascimento) ? Carbon::createFromFormat('Y-m-d', $row['nascimento'])->format('Ymd') : null;
                $block['raca_cor'] = 99;
                $block['etnia'] = null;
                $block['nacionalidade'] = 10;
                $block['origem'] = "BPA";

                $block['servico'] = $row['servico_bpa'];
                $block['classificacao'] = $row['class_bpa'];
                $block['equipe'] = null;
                $block['arena-equipe'] = null;
                $block['cnpj'] = null;
                $block['cep'] = CEPs::ValidaCEPExportacao($row['cep']);
                $block['codigo_logradouro'] = !empty($row['endereco_tipo']) ? $row['endereco_tipo'] : 81;
                $block['endereco'] = !empty($row['endereco']) ? $row['endereco'] : 'NAO INFORMADO';
                $block['complemento'] = $row['complemento'];
                $block['numero'] = !empty($row['numero']) ? $row['numero'] : '00';
                $block['bairro'] = !empty($row['bairro']) ? $row['bairro'] : 'NAO INFORMADO';
                $block['telefone'] = null;//Util::String4DB($row['celular']);
                $block['email'] = null;//strtolower($row['email']);
                $block['indentificacao-nacional'] = null;
                $block['correspondente'] = null;//"LF";

                $sequencial++;
                self::$totallinhas++;

                $nova_folha = ($block['cbo'] != $cbo_folha || $block['cns'] != $cnes_folha);

                if (($nova_folha && $count > 1) || $sequencial > 99) {
                    $folha++;
                    self::$totallinhas++;

                    if ($sequencial > 99) {
                        $sequencial = 1;
                    }

                }

                $block['folha'] = $folha;
                $_data['03'][] = self::MountBlock3FileBPA($block);

                $cbo_folha = $row['cbo'];
                $cnes_folha = $row['cns'];
                $count++;
            }

            $header['indicador'] = "01";
            $header['indicador-cabecalho'] = "#BPA#";
            $header['processamento'] = $params['ano'] . $params['mes'];
            $header['line'] = self::$totallinhas;// linhas
            $header['folha'] = self::$folhatotal++;; // folhas total
            $header['controle-dominio'] = null;
            $header['nome-orgao'] = null;//$headers['nome'];
            $header['sigla-orgao'] = null;//$headers['codigo'];
            $header['cpf-prestador'] = null;
            $header['nome-orgao-saude'] = null;//("SECRETARIA MUNICIPAL DE SÃO PAULO");
            $header['indicador-orgao'] = null;//"M";
            $header['versao-sistema'] = null;//"SIGA_15.03";
            $header['correspondente'] = null;//"LF";

            $_data['01'][] = self::MountHeadFileBPA($header);

            $export = array_merge($_data['01'], $_data['03']);

            file_put_contents(self::$path . $cbo . ".TXT", implode("\r\n", $export));
        }
    }

    private static function _Consolidado()
    {
        $params = self::$params;

        self::$folhatotal = 1;
        self::$totallinhas = 1;
        $sequencial = 1;
        $folha = 1;
        if ($params['lote'] == 10) {
            $folha = 900;
        }

        $headers = self::_BPAHeader($params);

        $blocks = self::_BPABocks($params, 1);

        foreach ($blocks as $row) {
            $block = null;

            $realizacao = $row['realizacao'];

            $realizacao_n = Carbon::create(substr($realizacao, 0, 4), substr($realizacao, -2));

            $diff_realizacao = $realizacao_n->diffInMonths(BI::$limite_competencia);

            if ($diff_realizacao > 2) {
                $_realizacao = BI::$limite_competencia->format('Ym');
            } else {
                $_realizacao = $realizacao_n->format('Ym');
            }

            $block['indentificacao'] = "02";
            $block['cnes'] = $headers['codigo'];
            $block['realizacao'] = $_realizacao;
            $block['cbo'] = $row['cbo'];
            $block['folha-bpa'] = $folha;
            $block['numero-sequencial'] = $sequencial;
            $block['codigo-procedimento'] = $row['sus'];
            $block['idade'] = null;
            $block['quantidade-procedimento'] = $row['quantidade'];
            $block['origem'] = "BPA";
            $block['correspondente'] = "LF";

            $_data['02'][] = self::MountBlock2FileBPA($block);

            $sequencial++;
            self::$totallinhas++;
            if ($sequencial > 20) {
                $sequencial = 1;
                $folha++;
                self::$folhatotal++;
            }
        }

        $header['indicador'] = "01";
        $header['indicador-cabecalho'] = "#BPA#";
        $header['processamento'] = self::$params['ano'] . self::$params['mes'];
        $header['line'] = self::$totallinhas;// linhas
        $header['folha'] = self::$folhatotal; // folhas total
        $header['controle-dominio'] = null;
        $header['nome-orgao'] = null;//$headers['nome'];
        $header['sigla-orgao'] = null;//$headers['codigo'];
        $header['cpf-prestador'] = null;
        $header['nome-orgao-saude'] = null;//("SECRETARIA MUNICIPAL DE SÃO PAULO");
        $header['indicador-orgao'] = null;//"M";
        $header['versao-sistema'] = null;//"SIGA_15.03";
        $header['correspondente'] = null;//"LF";

        $_data['01'][] = self::MountHeadFileBPA($header);

        $export = !empty($_data['02']) ? array_merge($_data['01'], $_data['02']) : $_data['01'];

        return file_put_contents(self::$path . "CONSOLIDADO.TXT", implode("\r\n", $export));
    }

    private static function _BPABocks($params, $forma_faturamento = 1)
    {
        $ultimo_dia = date("t", mktime(0, 0, 0, $params['mes'], '01', $params['ano']));

        $arenas = Arenas::getByLote($params['lote']);

        $data['inicial'] = "{$params['ano']}-{$params['mes']}-01 00:00:00";
        $data['final'] = "{$params['ano']}-{$params['mes']}-{$ultimo_dia} 23:59:59";

        $data = Atendimentos::select(
            \DB::raw("EXTRACT(YEAR_MONTH FROM agendas.data) AS realizacao"),
            \DB::raw('SUM(atendimento_procedimentos.quantidade) AS quantidade'),
            'procedimentos.sus',
            'procedimentos.cbo'
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('faturamento_procedimentos', function ($join) use ($params) {
                $join->on('atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
                    ->where('faturamento_procedimentos.lote', '=', $params['lote'])
                    ->where('faturamento_procedimentos.faturamento', '=', $params['faturamento'])
                    ->where('faturamento_procedimentos.status', '=', 1);
            })
            ->whereIn('agendas.arena', $arenas)
            ->where('agendas.data', '!=', '0000-00-00 00:00:00')
            ->where('procedimentos.forma_faturamento', $forma_faturamento)
            ->whereIn('atendimento_procedimentos.procedimento', Procedimentos::getProcedimentosDiagnostico())
            ->groupBy('procedimentos.sus', 'procedimentos.cbo')
            ->get()
            ->toArray();

        return $data;
    }

    private static function _BPABocks2($params, $forma_faturamento = 2)
    {
        $ultimo_dia = date("t", mktime(0, 0, 0, $params['mes'], '01', $params['ano']));

        $arenas = Arenas::getByLote($params['lote']);

        $data['inicial'] = "{$params['ano']}-{$params['mes']}-01 00:00:00";
        $data['final'] = "{$params['ano']}-{$params['mes']}-{$ultimo_dia} 23:59:59";

        $lote_id = self::$lote->id;

        $sql_query = "  SELECT  pro.cns 
                          FROM lote_profissionais 
                            JOIN lote_profissional_cbos ON lote_profissional_cbos.lote_profissional = lote_profissionais.id
                            JOIN cbo ON cbo.id = lote_profissional_cbos.cbo
                            JOIN profissionais AS pro ON pro.id = lote_profissionais.profissional
                        WHERE lote_profissionais.lote = {$lote_id}
                          AND cbo.codigo = procedimentos.cbo
                        LIMIT 1";

        $data = Atendimentos::select(
            [
                'agendas.id',
                'agendas.arena AS arena',
                'agendas.data AS created_at',
                'faturamento_procedimentos.quantidade',
                'pacientes.nascimento',
                'procedimentos.sus',
                'profissionais.cns AS cns_original',
                DB::raw("({$sql_query}) as cns"),
                'procedimentos.cbo',
                'procedimentos.cid_primario AS cid',
                'procedimentos.servico_bpa',
                'procedimentos.class_bpa',
                'pacientes.cns AS pacientes_cns',
                'pacientes.sexo',
                'pacientes.cidade',
                'pacientes.nome',
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
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('profissionais', 'atendimento_procedimentos.profissional', '=', 'profissionais.id')
            ->join('faturamento_procedimentos', function ($join) use ($params) {
                $join->on('atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
                    ->where('faturamento_procedimentos.lote', '=', $params['lote'])
                    ->where('faturamento_procedimentos.faturamento', '=', $params['faturamento'])
                    ->where('faturamento_procedimentos.status', '=', 1);;
            })
            ->where('procedimentos.forma_faturamento', $forma_faturamento)
            ->where('procedimentos.ativo', 1)
            ->whereIn('agendas.arena', $arenas)
            ->whereIn('atendimento_procedimentos.procedimento', Procedimentos::getProcedimentosDiagnostico())
            ->orderBy('procedimentos.cbo', 'ASC')
            ->orderBy('profissionais.cns', 'ASC')
            ->orderBy('agendas.data', 'ASC')
            ->get()
            ->toArray();

        $__data = [];
        foreach ($data as $key => $row) {
            $__data[$row['cbo']][] = $row;
        }

        return $__data;
    }

    private static function _BPAHeader($params)
    {
        $data = Lotes:: select(array('lotes.nome', 'lotes.codigo'))
            ->where('id', $params['lote'])
            ->get()
            ->toArray();

        return $data[0];
    }


    private static function MountBlock3FileBPA($row)
    {
        $data = array();

        $data[1] = Util::StrPadLeft($row['indentificacao'], 2);
        $data[2] = Util::StrPadLeft($row['cnes'], 7);
        $data[3] = Util::StrPadLeft($row['realizacao'], 6);
        $data[4] = Util::StrPadLeft($row['cns'], 15);
        $data[5] = Util::StrPadLeft($row['cbo'], 6);
        $data[6] = Util::StrPadLeft($row['atendimento'], 8);
        $data[7] = Util::StrPadLeft($row['folha'], 3);
        $data[8] = Util::StrPadLeft($row['sequencial'], 2);
        $data[9] = Util::StrPadLeft($row['codigo-procedimento'], 10);
        $data[10] = Util::StrPadLeft($row['pacientes_cns'], 15);
        $data[11] = Util::StrPadLeft($row['sexo'], 1);
        $data[12] = Util::StrPadLeft($row['cidade'], 6);
        $data[13] = Util::StrPadLeft($row['cid'], 4, ' ');
        $data[14] = Util::StrPadLeft($row['idade'], 3);
        $data[15] = Util::StrPadLeft($row['quantidade'], 6);
        $data[16] = Util::StrPadLeft($row['categoria'], 2);
        $data[17] = Util::StrPadLeft($row['autorizacao-estabelecimento'], 13, ' ');
        $data[18] = Util::StrPadLeft($row['origem'], 3);
        $data[19] = Util::StrPadRight($row['nome'], 30);
        $data[20] = Util::StrPadLeft($row['nascimento'], 8);
        $data[21] = Util::StrPadLeft($row['raca_cor'], 2);
        $data[22] = Util::StrPadLeft($row['etnia'], 4, ' ');
        $data[23] = Util::StrPadLeft($row['nacionalidade'], 3);

        $data[24] = ($row['servico'] == 0) ? Util::StrPadLeft(null, 3, ' ') : Util::StrPadLeft($row['servico'], 3);
        $data[25] = ($row['classificacao'] == 0) ? Util::StrPadLeft(null, 3, ' ') : Util::StrPadLeft($row['classificacao'], 3);
        $data[26] = Util::StrPadLeft($row['equipe'], 8, ' ');
        $data[27] = Util::StrPadLeft($row['arena-equipe'], 4, ' ');
        $data[28] = Util::StrPadLeft($row['cnpj'], 14, ' ');
        $data[29] = Util::StrPadLeft($row['cep'], 8, ' ');
        $data[30] = Util::StrPadLeft($row['codigo_logradouro'], 3);
        $data[31] = Util::StrPadRight($row['endereco'], 30, ' ');
        $data[32] = Util::StrPadRight($row['complemento'], 10, ' ');
        $data[33] = Util::StrPadLeft($row['numero'], 5, ' ');
        $data[34] = Util::StrPadRight($row['bairro'], 30, ' ');
        $data[35] = Util::StrPadLeft($row['telefone'], 11, ' ');
        $data[36] = Util::StrPadRight($row['email'], 40, ' ');
        $data[37] = Util::StrPadLeft($row['indentificacao-nacional'], 10, ' ');
        $data[38] = Util::StrPadLeft($row['correspondente'], 2, " ");

        return implode("", $data);
    }

    private static function MountBlock2FileBPA($row)
    {
        $data = array();

        $data[1] = Util::StrPadLeft($row['indentificacao'], 2);
        $data[2] = Util::StrPadLeft($row['cnes'], 7);
        $data[3] = Util::StrPadLeft($row['realizacao'], 6);
        $data[4] = Util::StrPadLeft($row['cbo'], 6);
        $data[5] = Util::StrPadLeft($row['folha-bpa'], 3);
        $data[6] = Util::StrPadLeft($row['numero-sequencial'], 2);
        $data[7] = Util::StrPadLeft($row['codigo-procedimento'], 10);
        $data[8] = Util::StrPadLeft($row['idade'], 3);
        $data[9] = Util::StrPadLeft($row['quantidade-procedimento'], 6);
        $data[10] = Util::StrPadLeft($row['origem'], 3);
        $data[11] = Util::StrPadLeft($row['correspondente'], 2, " ");

        return implode("", $data);
    }

    private static function MountHeadFileBPA($header)
    {
        $data = array();

        $data[1] = Util::StrPadLeft($header['indicador'], 2);
        $data[2] = Util::StrPadLeft($header['indicador-cabecalho'], 5);
        $data[3] = Util::StrPadLeft($header['processamento'], 6);
        $data[4] = Util::StrPadLeft($header['line'], 6);
        $data[5] = Util::StrPadLeft($header['folha'], 6);
        $data[6] = Util::StrPadLeft($header['controle-dominio'], 4);
        $data[7] = Util::StrPadRight($header['nome-orgao'], 30);
        $data[8] = Util::StrPadLeft($header['sigla-orgao'], 6);
        $data[9] = Util::StrPadRight($header['cpf-prestador'], 14);
        $data[10] = Util::StrPadRight($header['nome-orgao-saude'], 40);
        $data[11] = Util::StrPadLeft($header['indicador-orgao'], 1);
        $data[12] = Util::StrPadLeft($header['versao-sistema'], 10);
        $data[13] = Util::StrPadLeft($header['correspondente'], 2, " ");

        return implode("", $data);
    }

    private static function getCNES($cbo, $cns = null)
    {
        $__cbo = null;
        $cbos = self::$_cbos;

        if (!empty($cns)) {
            $_cbos = array_key_exists($cbo, $cbos) ? $cbos[$cbo] : null;

            if (!is_null($_cbos)) {
                $__cbo = array_key_exists($cns, $_cbos) ? $_cbos[$cns] : null;
            }
        }

        if (is_null($__cbo)) {
            $__cbo = array_key_exists($cbo, $cbos) ? $cbos[$cbo][array_rand($cbos[$cbo])] : null;
        }

        return $__cbo;
    }

    public static function metrasPorLoteLinhaCuidado($lote)
    {
        #lote 1
        $data[3][2]['min'] = 324;
        $data[3][2]['max'] = 360;

        $data[3][4]['min'] = 1620;
        $data[3][4]['max'] = 1800;

        $data[3][6]['min'] = 1215;
        $data[3][6]['max'] = 1350;

        $data[3][1]['min'] = 900;
        $data[3][1]['max'] = 1000;

        $data[3][3]['min'] = 450;
        $data[3][3]['max'] = 500;

        $data[3][5]['min'] = 6127;
        $data[3][5]['max'] = 6808;

        $data[3][7]['min'] = 18800;
        $data[3][7]['max'] = 23500;

        #lote 2
        $data[4][2]['min'] = 180;
        $data[4][2]['max'] = 200;

        $data[4][4]['min'] = 450;
        $data[4][4]['max'] = 500;

        $data[4][6]['min'] = 1620;
        $data[4][6]['max'] = 1800;

        $data[3][1]['min'] = 1440;
        $data[3][1]['max'] = 1600;

        $data[4][3]['min'] = 450;
        $data[4][3]['max'] = 500;

        $data[4][5]['min'] = 8658;
        $data[4][5]['max'] = 9620;

        $data[4][7]['min'] = 13600;
        $data[4][7]['max'] = 25000;

        #lote 3
        $data[5][2]['min'] = 144;
        $data[5][2]['max'] = 160;

        $data[5][4]['min'] = 360;
        $data[5][4]['max'] = 400;

        $data[5][6]['min'] = 810;
        $data[5][6]['max'] = 900;

        $data[5][1]['min'] = 1440;
        $data[5][1]['max'] = 1600;

        $data[5][3]['min'] = 360;
        $data[5][3]['max'] = 400;

        $data[5][5]['min'] = 3862;
        $data[5][5]['max'] = 4292;

        $data[5][7]['min'] = 8960;
        $data[5][7]['max'] = 11200;


        return $data[$lote];
    }

}