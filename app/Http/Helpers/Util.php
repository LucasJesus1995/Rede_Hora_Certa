<?php

namespace App\Http\Helpers;

use App\Agendas;
use App\ArenaEquipamentos;
use App\Arenas;
use App\AtendimentoAnamnenseRespostas;
use App\AtendimentoProcedimentos;
use App\Cidades;
use App\Estados;
use App\LinhaCuidado;
use App\LinhaCuidadoProcedimentos;
use App\Pacientes;
use App\Procedimentos;
use App\Profissionais;
use App\Roles;
use App\User;
use App\Usuarios;
use Carbon\Carbon;
use DateTime;
use FlyingLuscas\ViaCEP\ZipCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\VarDumper\Cloner\Data;

class Util
{

    public static function isConnected($uri = 'google.com')
    {
        $response = null;
        @system("ping -c 1 $uri > /tmp/connect-horacerta", $response);

        return ($response == 0);
    }

    public static function CheckPermissionAction($slug, $perm)
    {
        $return = false;
        $profile = User::getPerfil();
        if ($profile == 1) {
            return true;
        }

        $permission = Roles::getBySlug($slug);
        if (!empty($permission['id'])) {
            $return = false;
        }

        $permission_id = !empty($permission['id']) ? $permission['id'] : null;
        $permission_role = Roles::getRoleByPermissionProfile($permission_id, $profile);

        if (is_array($permission_role)) {
            if (array_key_exists($perm, $permission_role)) {
                $return = $permission_role[$perm] == 1;
            }
        }

        return $return;
    }

    public static function EnderecoTipo($key = null)
    {
        $data['081'] = "081 - " . Lang::get('app.rua');
        $data['008'] = "008 - " . Lang::get('app.avenida');
        $data['100'] = "100 - " . Lang::get('app.travessa');
        $data['031'] = "031 - " . Lang::get('app.estrada');
        $data['004'] = "004 - " . Lang::get('app.alameda');
        $data['065'] = "065 - " . Lang::get('app.praca');
        $data['074'] = "074 - " . Lang::get('app.passagem');
        $data['105'] = "105 - " . Lang::get('app.viela');
        $data['054'] = "054 - " . Lang::get('app.largo');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function Perfil($key = null)
    {
        return self::TypeProfissional($key);

        $data['1'] = Lang::get('app.enfermagem');
        $data['2'] = Lang::get('app.medicina');
        $data['3'] = Lang::get('app.administrador');
        $data['4'] = Lang::get('app.recepcao');
        $data['5'] = Lang::get('app.usuario-final');

        if ($key) {
            return array_key_exists($key, $data) ? $data[$key] : null;
        }

        return $data;
    }

    public static function MeioTransporte($key = null)
    {
        $data['1'] = Lang::get('app.trem');
        $data['2'] = Lang::get('app.metro');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function FormaFaturamento($key = null)
    {
        $data['1'] = Lang::get('app.consolidado');
        $data['2'] = Lang::get('app.individualizado');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function Ativo($key = null)
    {
        $data['1'] = Lang::get('app.sim');
        $data['0'] = Lang::get('app.nao');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function ValidaEtapas($key = null)
    {
        $data['1'] = Lang::get('app.agendamento');
        $data['2'] = Lang::get('app.enfermagem');
        $data['3'] = Lang::get('app.medico');
        $data['4'] = Lang::get('app.laudo');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function Nacionalidade($key = null)
    {
        $data['010'] = "010 - " . Lang::get('app.brasileiro');
        $data['020'] = "020 - " . Lang::get('app.naturalizado-brasileiro');
        $data['050'] = "050 - " . Lang::get('app.outros');

        $key = ($key) ? self::StrPadLeft($key, 3, 0) : false;
        if ($key) {
            return array_key_exists($key, $data) ? $data[$key] : null;
        }

        return $data;
    }

    public static function EstadoCivil($key = null)
    {
        $data['1'] = "SOLTEIRO (A)";
        $data['2'] = "CASADO (A)";
        $data['3'] = "DIVORCIADO (A)";
        $data['4'] = "VIÚVO (A)";
        $data['5'] = "SEPARADO (A)";

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function Sexo($key = null)
    {

        $data['2'] = Lang::get('app.feminino');
        $data['1'] = Lang::get('app.masculino');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function SexoProcedimento($key = null)
    {

        $data['3'] = 'Ambos';
        $data['2'] = Lang::get('app.feminino');
        $data['1'] = Lang::get('app.masculino');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function Sala($key = null)
    {

        $data['1'] = 'Sala 01';
        $data['2'] = 'Sala 02';
        $data['3'] = 'Sala 03';
        $data['4'] = 'Sala 04';
        $data['5'] = 'Sala 05';
        $data['6'] = 'Sala 06';
        $data['7'] = 'Sala 07';

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function TypeProfissional($key = null)
    {
        $data['6'] = Lang::get('app.atendente');
        $data['3'] = Lang::get('app.aux-enfermeiro');
        $data['2'] = Lang::get('app.enfermeiro');
        $data['1'] = Lang::get('app.medico');
        $data['4'] = Lang::get('app.tec-enfermagem');
        $data['5'] = Lang::get('app.tec-radiologia');
        $data['7'] = Lang::get('app.recepcao');

        if ($key) {
            return array_key_exists($key, $data) ? $data[$key] : null;
        }

        return $data;
    }

    public static function RacaCor($key = false)
    {
        $data['01'] = "001 - " . Lang::get('app.branca');
        $data['02'] = "002 - " . Lang::get('app.preta');
        $data['03'] = "003 - " . Lang::get('app.parda');
        $data['05'] = "005 - " . Lang::get('app.indigena');
        $data['04'] = "004 - " . Lang::get('app.amarela');
        $data['099'] = "099 - " . Lang::get('app.sem-informacao');

        if ($key) {
            $key = ($key) ? self::StrPadLeft($key, 2, 0) : false;
            return array_key_exists($key, $data) ? $data[$key] : ($key == 99) ? $data['099'] : null;
        }

        return $data;
    }

    public static function StatusAgenda($key = null)
    {
        $data['1'] = Lang::get('app.aberto');
        $data['2'] = Lang::get('app.atendimento');
        $data['3'] = Lang::get('app.remarcado');
        $data['4'] = Lang::get('app.encaixe');
        $data['5'] = Lang::get('app.nao-atendido');
        $data['6'] = Lang::get('app.finalizado');
        $data['8'] = "Finalizado (Cirurgico)";
        $data['10'] = Lang::get('app.finalizado-digitador');
        $data['7'] = Lang::get('app.falta');
        $data['0'] = Lang::get('app.cancelado');
        $data['98'] = "Faturado (Parcial)";
        $data['99'] = "Faturado (Total)";

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function StatusAgendaGordura()
    {
        $data['2'] = Lang::get('app.atendimento');
        $data['6'] = Lang::get('app.finalizado');
        $data['8'] = "Finalizado (Cirurgico)";
        $data['10'] = Lang::get('app.finalizado-digitador');

        return $data;
    }


    public static function statusAgendaRelatorio()
    {
        $_status = array();

        foreach (self::StatusAgenda() as $key => $label) {
            if (in_array($key, array(1, 2, 3, 5, 6, 7, 0, 10))) {
                switch ($key) {
                    case 0 :
                        $cor = "#ffc107";
                        break;
                    case 1 :
                        $cor = "#ffeb3b";
                        break;
                    case 2 :
                        $cor = "#cddc39";
                        break;
                    case 3 :
                        $cor = "#4caf50";
                        break;
                    case 5 :
                        $cor = "#009688";
                        break;
                    case 6 :
                        $cor = "#00bcd4";
                        break;
                    case 7 :
                        $cor = "#3f51b5";
                        break;
                    case 10 :
                        $cor = "#cddc39";
                        break;
                    default :
                        $cor = "#CCCCCC";
                        break;
                }

                $_status[$key]['label'] = $label;
                $_status[$key]['cor'] = $cor;
            }
        }

        return $_status;
    }

    public static function Medicos($full = false)
    {
        $key = 'get-medicos';
        $data = [];

        if (!Cache::has($key)) {
            $_data = Profissionais::select('nome', 'cro', 'id')
                ->where('type', 1)
                ->where('ativo', 1)
                ->orderBy('nome', 'asc')->get();

            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->id] = self::StrPadLeft($row->cro, 6) . " - {$row->nome}";
                }
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function Digitadores()
    {
        $key = 'get-digitador';
        $data = [];

        if (!Cache::has($key)) {
            $_data = Usuarios::select('id', 'name')->where('level', 10)->orderBy('name', 'ASC')->get();

            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->id] = self::String2DB($row->name);
                }
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getMedicosNome($key)
    {
        $medicos = self::Medicos();

        return (array_key_exists($key, $medicos)) ? $medicos[$key] : null;
    }

    public static function getPacienteNome($id)
    {
        $data = Pacientes::get($id);

        return !empty($data['nome']) ? $data['nome'] : $id;
    }

    public static function getArenaNome($id)
    {
        $data = Arenas::get($id);

        return !empty($data['nome']) ? $data['nome'] : $id;
    }

    public static function getLinhaCuidadoNome($id)
    {
        $data = LinhaCuidado::get($id);

        return !empty($data['nome']) ? $data['nome'] : $id;
    }

    public static function Atendente()
    {
        $key = 'get-atendente';

        if (!Cache::has($key)) {
            $data = Profissionais::where('type', 6)->orderBy('nome', 'asc')->lists('nome', 'id')->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getLinhaCuidado($id)
    {
        $key = 'get-linha-cuidado-' . $id;

        if (!Cache::has($key)) {
            $data = LinhaCuidado::find($id);

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }


        return $data;
    }

    public static function getLinhaCuidadoEspecialidade($key = null)
    {
        $data['1'] = "Diagnostico";
        $data['2'] = "Cirurgico";

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getProcedimentoObrigatorioByLinhaCuidado($linha_cuidado)
    {

        $procedimentos = Procedimentos::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

        $res = LinhaCuidadoProcedimentos::select([
            'procedimentos.id',
            'procedimentos.nome',
            'procedimentos.sus',
            'procedimentos.saldo',
            'procedimentos.maximo',
            'procedimentos.quantidade',
            'procedimentos.obrigatorio',
            'procedimentos.multiplicador',
            'procedimentos.multiplicador_medico'
        ])
            ->join('procedimentos', 'procedimentos.id', '=', 'linha_cuidado_procedimentos.procedimento')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('procedimentos.obrigatorio', true)
            ->where('procedimentos.ativo', true)
            ->orderBy('nome', 'asc');

        if ($procedimentos) {
            $res->orWhere(function ($query) use ($linha_cuidado, $procedimentos) {
                $query->where('procedimentos.ativo', true)
                    ->whereIn('procedimentos.id', $procedimentos)
                    ->where('procedimentos.obrigatorio', true);
            });
        }


        $data = $res->get()->toArray();

        return (count($data)) ? $data : null;
    }

    public static function getProcedimentoByLinhaCuidado($linha_cuidado)
    {
        $key = 'get-procedimento-linha-cuidado-' . $linha_cuidado;

        if (!Cache::has($key)) {
            $data = null;

            $procedimentos = Procedimentos::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

            $res = LinhaCuidadoProcedimentos::select([
                'procedimentos.id',
                'procedimentos.nome',
                'procedimentos.sus',
                'procedimentos.saldo',
                'procedimentos.maximo',
                'procedimentos.quantidade',
                'procedimentos.obrigatorio'
            ])
                ->join('procedimentos', 'procedimentos.id', '=', 'linha_cuidado_procedimentos.procedimento')
                ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
                ->where('procedimentos.ativo', true)
                ->where('procedimentos.operacional', true)
                ->orderBy('nome', 'asc')
                ->distinct();

            if ($procedimentos) {
                $res->orWhere(function ($query) use ($linha_cuidado, $procedimentos) {
                    $query->where('procedimentos.ativo', true)
                        ->where('procedimentos.operacional', true)
                        ->whereIn('procedimentos.id', $procedimentos);
                });
            }


            $data = $res->get()->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }


    public static function Timestamp2DB($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('d/m/Y H:i:s', $data)->format('Y-m-d H:i:s');
        }

        return $data;
    }

    public static function DBTimestamp2User2($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('d/m/Y H:i:s');
        }

        return $data;
    }

    public static function DBTimestamp2UserDate2($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('M j, Y');
        }

        return $data;
    }

    public static function DBTimestamp2User($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('M j, Y H:i');
        }

        return $data;
    }

    public static function DBTime2User($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('H:i:s', $data)->format('H:i');
        }

        return $data;
    }

    public static function DBTimestamp2UserDate($data = null)
    {
        if ($data) {
            return (self::validateDate($data)) ? Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('d/m/Y') : null;
        }

        return $data;
    }

    public static function DBTimestamp2UserDate3($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('Y-m-d');
        }

        return $data;
    }

    public static function DBTimestamp2UserTime2($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('H:i');
        }

        return $data;
    }

    public static function DBTimestamp2UserTime($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('H:i:s');
        }

        return $data;
    }

    public static function Date2DB($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        }

        return $data;
    }

    public static function DB2User($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d', $data)->format('d/m/Y');
        }

        return $data;
    }

    public static function DB2UserCard($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d', $data)->format('d.m.Y');
        }

        return $data;
    }


    public static function DB2UserDiaMes($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d', $data)->format('d/m');
        }

        return $data;
    }

    public static function DB2Users($data = null)
    {
        if ($data) {
            return Carbon::createFromFormat('Y-m-d', $data)->format('d/m/Y');
        }

        return $data;
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function getCidadeNameById($id = null)
    {
        if ($id) {
            $data = Cidades::find($id);

            if (!empty($data->nome)) {
                $estado = Estados::find($data->estado);

                if (!empty($estado->sigla)) {
                    return $data->nome . '/' . $estado->sigla;
                }
            }
        }

        return null;
    }

    public static function String2DB($string)
    {
        return strtoupper(preg_replace('/[`^~\'"]/', null, self::RemoveAcentos($string)));
    }

    // criado em 30-03 remover ()
    public static function String4DB($string)
    {
        return strtoupper(preg_replace('/[\(\)]/', null, self::RemoveAcentos($string)));
    }


    public static function RemoveAcentos($texto)
    {
        return preg_replace(array(
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/",
            "/(ç)/",
            "/(Ç)/"
        ), explode(" ", "a A e E i I o O u U n N c C"), $texto);

        $str = htmlentities($texto, ENT_COMPAT, 'UTF-8');
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1', $str);

        return html_entity_decode($str);
    }

    public static function getRespostaAtendimento($atendimento)
    {
        $data['1'] = array();
        $data['2'] = array();
        $data['3'] = array();
        $data['4'] = array();
        $data['5'] = array();
        $data['6'] = array();
        $data['7'] = array();
        $data['8'] = array();
        $data['9'] = array();
        $data['10'] = array();

        $res = AtendimentoAnamnenseRespostas::where(array('atendimento' => $atendimento))->get()->toArray();


        if ($res) {
            foreach ($res as $key => $row) {
                $data[$row['tipo']][$row['anamnense_perguntas']] = array(
                    'value' => $row['value'],
                    'value_descricao' => $row['value_descricao'],
                    'observacao' => $row['observacao'],
                    'anamnense_perguntas' => $row['anamnense_perguntas'],
                );
            }
        }

        return $data;
    }

    public static function CloseTags($html)
    {
        #put all opened tags into an array
        preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
        $openedtags = $result[1];

        #put all closed tags into an array
        preg_match_all("#</([a-z]+)>#iU", $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }

        $openedtags = array_reverse($openedtags);

        # close tags
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= "</" . $openedtags[$i] . ">";
            } else {
                unset ($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    public static function getDia()
    {
        for ($i = 1; $i < 32; $i++) {
            $data[$i] = str_pad($i, 2, "0", STR_PAD_LEFT);
        }

        return $data;
    }

    public static function getAnos()
    {
        for ($i = 2016; $i <= date('Y'); $i++) {
            $data[$i] = $i;
        }

        return $data;
    }

    public static function getMes()
    {
        $mes["01"] = 'Janeiro';
        $mes["02"] = 'Fevereiro';
        $mes["03"] = 'Março';
        $mes["04"] = 'Abril';
        $mes["05"] = 'Maio';
        $mes["06"] = 'Junho';
        $mes["07"] = 'Julho';
        $mes["08"] = 'Agosto';
        $mes["09"] = 'Setembro';
        $mes["10"] = 'Outubro';
        $mes["11"] = 'Novembro';
        $mes["12"] = 'Dezembro';

        return $mes;
    }

    public static function getMesDiante()
    {
        $mes["01"] = 'Janeiro';
        $mes["02"] = 'Fevereiro';
        $mes["03"] = 'Março';
        $mes["04"] = 'Abril';
        $mes["05"] = 'Maio';
        $mes["06"] = 'Junho';
        $mes["07"] = 'Julho';
        $mes["08"] = 'Agosto';
        $mes["09"] = 'Setembro';
        $mes["10"] = 'Outubro';
        $mes["11"] = 'Novembro';
        $mes["12"] = 'Dezembro';

        $_mes = [];
        foreach ($mes as $item) {

        }

        return $mes;
    }

    public static function getMesNome($mes)
    {
        $mes = self::StrPadLeft($mes, 2);

        switch ($mes) {
            case "01":
                $mes = 'Janeiro';
                break;
            case "02":
                $mes = 'Fevereiro';
                break;
            case "03":
                $mes = 'Março';
                break;
            case "04":
                $mes = 'Abril';
                break;
            case "05":
                $mes = 'Maio';
                break;
            case "06":
                $mes = 'Junho';
                break;
            case "07":
                $mes = 'Julho';
                break;
            case "08":
                $mes = 'Agosto';
                break;
            case "09":
                $mes = 'Setembro';
                break;
            case "10":
                $mes = 'Outubro';
                break;
            case "11":
                $mes = 'Novembro';
                break;
            case "12":
                $mes = 'Dezembro';
                break;
        }

        return $mes;
    }

    public static function getMesByDate($data)
    {
        $date = explode('-', $data);

        return self::getMesNome($date[1]);
    }

    public static function getSemanaByDate($data)
    {
        $date = Carbon::createFromFormat("Y-m-d", $data);

        return self::diaSemana($date->weekOfMonth);
    }

    public static function getDiaByDate($data)
    {
        $date = Carbon::createFromFormat("Y-m-d", $data);

        return $date->format("d");
    }

    public static function getMesNomeAbreviado($mes)
    {
        $mes = self::StrPadLeft($mes, 2);

        switch ($mes) {
            case "01":
                $mes = 'JAN';
                break;
            case "02":
                $mes = 'FEV';
                break;
            case "03":
                $mes = 'MAR';
                break;
            case "04":
                $mes = 'ABR';
                break;
            case "05":
                $mes = 'MAI';
                break;
            case "06":
                $mes = 'JUN';
                break;
            case "07":
                $mes = 'JUL';
                break;
            case "08":
                $mes = 'AGO';
                break;
            case "09":
                $mes = 'SET';
                break;
            case "10":
                $mes = 'OUT';
                break;
            case "11":
                $mes = 'NOV';
                break;
            case "12":
                $mes = 'DEZ';
                break;
        }

        return $mes;
    }

    public static function diaSemana($i)
    {
        $semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

        return $semana[$i];
    }


    public static function diaSemanaAbreviado($key = null)
    {
        $data[0] = "DOM";
        $data[1] = "SEG";
        $data[2] = "TER";
        $data[3] = "QUA";
        $data[4] = "QUI";
        $data[5] = "SEX";
        $data[6] = "SAB";

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getUltimosAnosRetroativo($qts = 3)
    {
        $year = array();
        for ($i = date('Y') + 1; $i >= (date('Y') - $qts); $i--) {
            $year[$i] = $i;
        }

        return $year;
    }

    public static function getUltimosAnos($qts)
    {
        $year = array();
        for ($i = date('Y'); $i >= (date('Y') - $qts); $i--) {
            $year[$i] = $i;
        }

        return $year;
    }

    public static function getUltimosDiaMes($ano, $mes)
    {
        return date("t", mktime(0, 0, 0, $mes, '01', $ano));;
    }

    public static function StrPadLeft($string, $size, $complete = '0')
    {
        $string = trim($string);

        if (strlen($string) > $size) {
            return substr($string, 0, $size);
        }

        return str_pad($string, $size, $complete, STR_PAD_LEFT);
    }


    public static function StrPadRight($string, $size, $complete = " ")
    {
        $string = trim($string);

        if (strlen($string) > $size) {
            return substr($string, 0, $size);
        }

        return str_pad($string, $size, $complete, STR_PAD_RIGHT);
    }

    public static function IsProfissionalMedico()
    {
        $id = Auth::user()->profissional;

        $res = Profissionais::all()->where('id', $id)->where('type', '2')->first();

        return !empty($res);
    }

    public static function RecursivePath($path, $mode = 0777)
    {
        $dirs = explode(DIRECTORY_SEPARATOR, $path);

        $path = '.';
        for ($i = 0; $i < count($dirs); ++$i) {
            $path .= DIRECTORY_SEPARATOR . $dirs[$i];
            if (!is_dir($path) && !mkdir($path, $mode, true)) {
                return false;
            }
        }
        return $path;
    }

    public static function RemoverPath($path)
    {
        if (file_exists($path)) {
            if ($dd = opendir($path)) {
                while (false !== ($Arq = readdir($dd))) {
                    if ($Arq != "." && $Arq != "..") {
                        $Path = "{$path}/$Arq";
                        if (is_dir($Path)) {
                            self::removerPath($Path);
                        } elseif (is_file($Path)) {
                            unlink($Path);
                        }
                    }
                }
                closedir($dd);
            }
            rmdir($path);
        }
    }

    public static function ForceContent($path, $file, $contents)
    {
        self::RecursivePath($path);

        file_put_contents($path . $file, $contents);

        return $path . $file;
    }

    public static function Idade($nascimento)
    {
        $nascimento = explode("-", $nascimento);

        return is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1],
            $nascimento[2])->age : null;
    }

    public static function TempoCalculo($in, $out)
    {
        if (empty($in) || empty($out)) {
            return null;
        }

        $date_in = Carbon::createFromFormat('Y-m-d H:i:s', $in);
        $date_out = Carbon::createFromFormat('Y-m-d H:i:s', $out);

        $diff = $date_in->diffInSeconds($date_out);
        return $diff;
    }

    public static function TempoAtendimentoDate($in, $out)
    {
        return Util::DBTimestamp2User($in) . " ~ " . Util::DBTimestamp2UserTime2($out) . " (" . gmdate("H:i:s",
                Util::TempoCalculo($in, $out)) . ")";
    }

    public static function TempoAtendimentoDate2($in, $out)
    {
        return Util::DBTimestamp2User($in) . " ~ " . Util::DBTimestamp2UserTime2($out) . " (" . gmdate("H:i:s",
                Util::TempoCalculo($in, $out)) . ")";
    }

    public static function removeCookie()
    {
        Cookie::queue(Cookie::forget('doctor'));
    }

    public static function getDataDigitadoraMedico()
    {
        $data = self::getDataDigitadora();

        return !empty($data['doctor']) ? self::getTipo($data['doctor'], 'medico') : "";
    }

    public static function getDataDigitadora()
    {

        if (!empty(session('digitador'))) {
            $digitador = session('digitador');
        } else {
            $digitador = [
                'doctor' => !empty(Cookie::get('doctor')) ? Cookie::get('doctor') : null
            ];
        }

        $data['doctor'] = $digitador['doctor'];

        return $data;
    }

    public static function getUser()
    {
        return (Auth::check()) ? Auth::user()->id : null;
    }

    public static function getNivel()
    {
        return Auth::user()->level;

    }

    public static function getUserName()
    {
        return Auth::user()->name;
    }

    public static function getUserEmail()
    {
        return Auth::user()->email;
    }

    public static function getPorcentagemEquivalente($equivale, $total)
    {
        return ($equivale / 100) * $total;
    }

    public static function getPorcentagem($v1, $v2)
    {
        if (intval($v1) == 0 || intval($v2) == 0) {
            return 0;
        }

        return number_format((($v1 * 100) / $v2), 2, '.', '');
    }

    public static function getLaudoResultados($key = null)
    {
        $data['1'] = Lang::get('app.normal');
        $data['2'] = Lang::get('app.alterado');
        $data['3'] = Lang::get('app.suspeita-neoplasia');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function statusLaudo($key = null)
    {
        $data['1'] = Lang::get('app.positivo');
        $data['2'] = Lang::get('app.negativo');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getInspireCIES($inspire = false)
    {
        $data[0] = "Nossa missão é atender a necessidade de saúde integral do ser humano com qualidade, tecnologia e  agilidade no conceito de tratar, educar e prevenir.";
        $data[1] = "Nossa visão é ser reconhecido mundialmente como o melhor atendimento médico móvel.";
        $data[2] = "Valores do CIES: Trabalho em equipe com humildade; Servir as pessoas com respeito e equidade; Integridade e Transparência na Prestação de contas. Esses valores compõem o DNA do Amor";
        $data[3] = "Todas as unidades móveis do CIES são equipadas com aparelhos de diagnóstico de última geração e consultórios para o atendimento da população de alta vulnerabilidade social.";
        $data[4] = "CIES está trabalhando na conscientização da população sobre a própria saúde principalmente no que diz respeito a cuidados básicos e prevenção de doenças, bem como utilizar de maneira consciente os serviços médicos públicos.";
        $data[5] = "Desde 2008, a equipe do Projeto CIES, por meio da Carreta da Saúde, dos Boxes da Saúde e da Van da Saúde, além dos Arranjos Produtivos Locais, atendeu 500 mil pacientes, em sete diferentes estados brasileiros e mais de 20 especialidades médicas.";
        $data[6] = "Objetivo CIES é dar à população, acesso a exames médicos de média complexidade, como endoscopia, mamografia e ultrassonografia, através das unidades médicas móveis.";

        if ($inspire) {
            return $data[rand(0, 6)];
        }

        return $data;
    }

    public static function limitarTexto($texto, $limite = 50)
    {
        $contador = strlen($texto);
        if ($contador >= $limite) {
            $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
            return $texto;
        } else {
            return $texto;
        }
    }

    public static function digits($data)
    {
        return preg_replace("/[^0-9]/", "", $data);
    }

    public static function anoMes()
    {
        $data = [];
        $data[date('Y')] = [];
        $data[date('Y') - 1] = [];
        $data[date('Y') - 2] = [];

        $meses = self::getMes();
        foreach ($data as $key => $ano) {
            foreach ($meses as $_key => $mes) {
                if (date('Y') == $key && date('m') < $_key) {
                    continue;
                }

                $data[$key][$key . "-" . $_key] = $mes;
            }
        }

        return $data;
    }

    public static function datesMonth($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $dt = Carbon::create($year, $month, $i);
            $dates_month[$dt->weekOfYear][$dt->dayOfWeek] = $dt->toDateString();
        }

        return $dates_month;
    }

    public static function getStatusFaturamentoLabel($status = null)
    {
        if ($status) {
            if (in_array($status, array(1, 2, 3))) {
                switch ($status) {
                    case 1 :
                        $class = "bg-default";
                        $descricao = "Aberto";
                        break;
                    case 2 :
                        $class = "bg-success";
                        $descricao = "Em Processamento";
                        break;
                    case 3 :
                        $class = "bg-danger";
                        $descricao = "Finalizado";
                        break;
                    default :
                        $class = null;
                        $descricao = null;
                        break;
                }

                return "<label class='label {$class}'>{$descricao}</label>";
            }
        }

        return $status;
    }

    public static function getTipoFinalizacaoRelatorioAtendimento($key = null)
    {
        $data['6'] = Lang::get('app.faturista');
        $data['10'] = Lang::get('app.digitador');

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function cores()
    {
        $data[] = "#DCDCDC";
        $data[] = "#FFE4C4";
        $data[] = "#FFDEAD";
        $data[] = "#FFF8DC";
        $data[] = "#E6E6FA";
        $data[] = "#FFE4E1";
        $data[] = "#2F4F4F";
        $data[] = "#696969";
        $data[] = "#708090";
        $data[] = "#191970";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";
        $data[] = "";

        return $data;
    }

    public static function getUriImageProjetoByAgenda($agenda)
    {
        $unidade = Agendas::getUnidade($agenda);
        switch ($unidade) {
//            case 14 :
//                $image =  "<img class='img-responsive' style='height: 60px;' src='/src/image/logo/sorocaba/saude-em-dia.png'>" ;
//                break;
//            case 36 :
//                $image = "<img class='img-responsive' style='height: 60px;' src='/src/image/logo/cies.png'>";
//                break;
            default :
                //$image = "<img class='img-responsive' style='width: 150px;' src='/src/image/hora-certa.png'>";
                $image = "<img style='height: 60px;' src='src/image/logo/cies.png'>";
                break;
        }

        return $image;
    }


    public static function getUriImagePrefeituraByAgenda($agenda = null)
    {
        $unidade = ($agenda) ? Agendas::getUnidade($agenda) : 0;

        switch ($unidade) {
//            case 14 :
//                $image =  "<img class='img-responsive' style='height: 40px;' src='/src/image/logo/sorocaba/prefeitura.png'>" ;
//                break;
            default :
                $image = "<img class='img-responsive' style='height: 40px;' src='src/image/logo/prefeitura.png'>";
                break;
        }

        return $image;
    }


    public static function setCache($key, $params = null)
    {
        $key = $key . '-user-' . Auth::user()->id;
        $data = null;

        if ($params) {
            Cache::put($key, $params, CACHE_WEEK);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function setCookie($key, $value = null)
    {
        $data = null;

        if ($value) {
            setcookie($key, $value, time() + 3600 * 24 * 7);
        } else {
            $data = !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        }

        return $data;
    }

    public static function getCEP($cep)
    {
        $key = "get-cep-" . $cep;
        $data = null;

        if (!Cache::has($key)) {
            $zipcode = new ZipCode;

            $address = $zipcode->find($cep)->toArray();
            if (!empty($address) && !empty($address['street'])) {
                $data['endereco'] = $address['street'];
                $data['bairro'] = $address['neighborhood'];
                $data['ibge'] = $address['ibge'];
                $data['cidade'] = null;//$address['city'];

                $cidade = Cidades::getByIbge($data['ibge']);
                if (count($cidade)) {
                    $data['cidade']['id'] = $cidade->id;
                    $data['cidade']['nome'] = $cidade->nome;
                    $data['cidade']['estado']['id'] = $cidade->estado;
                    $data['cidade']['estado']['nome'] = $address['state'];
                }

                Cache::put($key, $data, CACHE_WEEK);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function periodoMesPorAnoMes($ano, $mes)
    {
        $date = Carbon::create($ano, $mes, '01', '0', '0', '0');

        return [
            'start' => $date->toDateTimeString(),
            'end' => $date->lastOfMonth()->hour(23)->minute(59)->second(59)->toDateTimeString()
        ];
    }


    public static function calculaIdade($nascimento, $atendimento = null)
    {
        $idade = null;

        if ($nascimento && strstr($nascimento, '-')) {
            $nascimento = explode("-", $nascimento);
            $_nascimento = mktime(0, 0, 0, $nascimento[1], $nascimento[2], $nascimento[0]);

            if ($atendimento && strstr($atendimento, '-')) {
                $data = explode("-", $atendimento);
                $data_atendimento = mktime(0, 0, 0, $data[1], $data[2], $data[0]);

                $idade = floor((((($data_atendimento - $_nascimento) / 60) / 60) / 24) / 365.25);
            } else {
                $idade = \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age;
            }
        }

        return $idade;
    }

    public static function getListDiaUteisAnosMes($ano, $mes)
    {
        $ultimo_dia = self::getUltimosDiaMes($ano, $mes);

        $_data = Carbon::createFromDate($ano, $mes, 1);

        $data[] = $_data->format('Y-m-d');;
        for ($i = 0; $i < ($ultimo_dia - 1); $i++) {
            $__data = $_data->addDay(1);

            // if(!$__data->isWeekend())
            $data[] = $__data->format('Y-m-d');

        }

        return $data;
    }

    public static function getDiasRepouso()
    {
        $key = 'get-dias-repouso1';

        if (!Cache::has($key)) {
            $dias = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60];
            foreach ($dias as $dia) {
                $data[$dia] = $dia . ' dia(s)';
            }

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function NomeSobrenome($nome)
    {
        $_nome = explode(" ", trim($nome));
        $nome = $_nome[0];
        if (!empty($_nome[1])) {
            $nome .= " " . end($_nome);
        }

        return $nome;
    }

    public static function getHideLaudoAtendimento($status, $agenda)
    {
        $perfil = User::getPerfil();
        $visible = true;

        switch ($status) {
            case "0":
            case "6":
            case "7":
            case "10":
            case "98":
            case "99":
                $visible = false;
                break;
        }

        $data = new Carbon(Agendas::get($agenda)['data']);

        if ($status == 10 && $perfil == 10) {
            if ($data->diffInDays(Carbon::now()) <= 4) {
                $visible = true;
            }
        }

        return $visible;
    }

    public static function somenteNumeros($data)
    {
        return preg_replace('/[^0-9]/', '', $data);
    }

    public static function getArenaEquipamentoNome($equipamento = null)
    {
        if (!is_null($equipamento)) {
            $data = ArenaEquipamentos::get($equipamento);
            $equipamento = !empty($data->nome) ? $data->nome : null;
        }

        return $equipamento;
    }

    public static function DateObject($data)
    {
        return new Carbon($data);
    }

    public static function TipoRelatorio($key = null)
    {
        $data['0'] = "Produzido";
        $data['1'] = "Faturado";

        if (!is_null($key) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function getListIntervalIdade($key = null)
    {
        $data['0'] = "0 ~ 9";
        $data['1'] = "10 ~ 19";
        $data['2'] = "20 ~ 29";
        $data['3'] = "30 ~ 39";
        $data['4'] = "40 ~ 49";
        $data['5'] = "50 ~ 64";
        $data['6'] = "65 ou +";

        if (!is_null($key) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function dateExtensoCidade($data = null, $cidade)
    {
        if (is_null($data)) {
            $data = date('Y-m-d');
        }

        $ano = substr($data, '0', 4);
        $dia = substr($data, '8', 2);
        $mes = strtolower(self::getMesNome(substr($data, '5', 2)));

        return "{$cidade}, {$dia} de {$mes} de {$ano}";
    }

    public static function getTipoAtendimento($key = null)
    {
        $data['4'] = "Consulta Pré";
//        $data['1'] = "Consulta Pré - Cirúrgica";
//        $data['2'] = "Cirurgia";
        $data['5'] = "Retorno Pré";
        $data['3'] = "Consulta Pós";
        $data['6'] = "Preparo de Colono";
        $data['7'] = "Biometria";
        $data['8'] = "Yag Laser";
        $data['9'] = "USG Ocular";
        $data['10'] = "Cirurgia de Catarata";
        $data['11'] = "Cirurgia de Pterígeo";
        $data['12'] = "Cirurgia de Vasectomia";
        $data['13'] = "Cirurgia de Hérnia";
        $data['14'] = "Escleroterapia";
        $data['15'] = "Pós de USG Ocular";
        $data['16'] = "Encaixe Diagnóstico";
        $data['17'] = "Encaixe Consulta Pré";
        $data['18'] = "Encaixe Retorno Pré";
        $data['19'] = "Encaixe Consulta Pós";
        $data['20'] = "Encaixe";
        $data['21'] = "Encaixe Biometria";
        $data['22'] = "Encaixe Yag laser";
        $data['23'] = "Encaixe Usg Ocular";
        $data['24'] = "Encaixe Cirurgia de Catarata";
        $data['25'] = "Encaixe Cirurgia de Pterígio";
        $data['26'] = "Encaixe de Cirurgia de Vasectomia";
        $data['27'] = "Encaixe de Escleroterapia";
        $data['28'] = "Encaixe de Pós USG Ocular";

        if (!is_null($key) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function clearFields($data, $tipo = null, $size = null)
    {
        $string = is_array($data) || is_object($data) ? null : $data;

        if ($tipo) {
            $string = self::getTipo($string, $tipo, $size);
            if (is_array($string)) {
                $string = $data;
            }
        }
        return $string;

    }

    private static function getTipo($string, $tipo, $size = null)
    {
        switch ($tipo) {
            case "arena" :
                $data = Arenas::get($string);
                $string = !empty($data['nome']) ? $data['nome'] : $string;
                break;
            case "equipamento" :
                $data = ArenaEquipamentos::get($string);
                $string = !empty($data->nome) ? $data->nome : $string;
                break;
            case "linha_cuidado" :
                $data = LinhaCuidado::get($string);
                $string = !empty($data['nome']) ? $data['nome'] : $string;
                break;
            case "medico" :
                $data = Profissionais::getMedicoByID($string);
                $string = !empty($data->nome) ? $data->nome : $string;
                break;
            case "periodo" :
                $string = DataHelpers::getPeriodo($string);
                break;
            case "date" :
                $string = Util::DB2User($string);
                break;
            case "id" :
                $string = self::StrPadLeft($string, 6);
                break;
            case "time" :
                $string = substr($string, 0, 5);
                break;

        }


        return $string;
    }

    public static function calculaIntervaloOferta($hora_inicial = null, $hora_final = null, $quantidade = null)
    {
        if (!empty($hora_inicial) && !empty($hora_final) && !empty($quantidade)) {
            $diff = (strtotime($hora_final) - strtotime($hora_inicial)) / $quantidade;

            return gmdate("H:i", $diff);
        }
    }

    public static function getMotivosRemarcacao($key = null)
    {
        $data['1'] = "Problema com a guia";
        $data['2'] = "Preparo inadequado";
        $data['3'] = "Falta de Acompanhante ou documento";
        $data['4'] = "Atraso";
        $data['5'] = "Quebra de equipamento";
        $data['6'] = "Falta de médico";

        if (!is_null($key) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;
    }

    public static function isSerialized($data, $strict = true)
    {
        // If it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // Or else fall through.
            case 'a':
            case 'O':
                return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool)preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
        }
        return false;

    }


}