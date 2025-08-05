<?php

namespace App\Http\Controllers\Admin;

use App\Agendas;
use App\Arenas;
use App\FaturamentoProcedimento;
use App\Http\Controllers\TraitController;
use App\Http\Helpers\UsuarioHelpers;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\LinhaCuidadoProcedimentos;
use App\Procedimentos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class FaturamentoProcedimentoController extends Controller
{
    public $model = 'FaturamentoProcedimento';

    use TraitController;

    public function __construct()
    {
        $this->title = "app.faturamento";

        parent::__construct();
    }

    public function getMetaProcedimento()
    {
        $view = View("admin.{$this->layout}.meta-procedimento")->with('title', $this->title);

        return $view;
    }

    public function postMetaProcedimento()
    {
        $view = View("admin.{$this->layout}.meta-procedimento-grid")->with('title', $this->title);

        try {
            $data = Input::all();
            $view->params = $data;

            if (empty($data['arena']))
                throw new \Exception("Informe a arena para pesquisa.");

            if (empty($data['ano']))
                throw new \Exception("Informe um ano para pesquisa.");

            if (empty($data['mes']))
                throw new \Exception("Informe um mês para pesquisa.");

            $linha_cuidado = LinhaCuidado::ByArena($data['arena'], true);
            $view->arena = Arenas::Combo($data['arena']);
            $procedimentos = Procedimentos::select(['procedimentos.nome', 'procedimentos.id', 'procedimentos.sus'])->orderBy('procedimentos.nome', 'asc')->where('ativo', '1')->get()->toArray();

            $view->linha_cuidado = $linha_cuidado;

            $response = array();
            if ($procedimentos) {
                foreach ($procedimentos as $k => $r) {
                    $response[$k]['procedimento'] = $r;

                    if ($linha_cuidado) {
                        foreach ($linha_cuidado as $kl => $l) {
                            $faturamento_procedimentos = FaturamentoProcedimento::getByParams($data['arena'], $l['id'], $r['id'], $data['ano'], $data['mes']);

                            $response[$k]['linhas_cuidado'][$kl]['id'] = !empty($faturamento_procedimentos) ? $faturamento_procedimentos['id'] : null;
                            $response[$k]['linhas_cuidado'][$kl]['valor'] = !empty($faturamento_procedimentos) ? $faturamento_procedimentos['valor'] : null;
                            $response[$k]['linhas_cuidado'][$kl]['linha_cuidado_id'] = !empty($l['id']) ? $l['id'] : null;
                            $response[$k]['linhas_cuidado'][$kl]['procedimento_id'] = !empty($r['id']) ? $r['id'] : null;
                        }
                    }

                }
            }

            $view->response = $response;
        } catch (\Exception $e) {
            $view->error = $e->getMessage();
        }

        return $view;
    }

    public function postMetaProcedimentoSave()
    {
        try {
            $response['success'] = true;

            $data = Input::all();

            $save = !empty($data['id']) ? FaturamentoProcedimento::find($data['id']) : new FaturamentoProcedimento();
            $save->arena = $data['arena'];
            $save->procedimento = $data['procedimento'];
            $save->linha_cuidado = $data['linha_cuidado'];
            $save->mes = $data['mes'];
            $save->ano = $data['ano'];
            $save->valor = $data['valor'];
            $save->save();

            $response['id'] = $save->id;
        } catch (\Exception $e) {
            $response['success'] = false;
        }

        return json_encode($response);
    }


    public function getFechamento()
    {
        $title = UsuarioHelpers::isNivelCirurgico() ? 'Cirurgico (Fechamento)' : 'Faturamento (Fechamento)';

        $view = View("admin.faturamento.fechamento")->with('title', $title);
        $view->arenas = \App\Arenas::Combo();

        return $view;
    }

    public function getFechamentoPesquisa()
    {
        $view = View("admin.faturamento.grid-faturamento");
        try {
            $params = Input::all();

            $sql = Agendas::distinct()->select(
                'agendas.id',
                'agendas.data',
                'pacientes.nome AS paciente_nome',
                'pacientes.cns AS paciente_cns',
                'pacientes.cpf AS paciente_cpf',
                'agendas.paciente',
                'agendas.arena',
                'agendas.status',
                'agendas.linha_cuidado',
                'agendas.ativo',
                'pacientes.nome',
                'arenas.alias AS arenas_nome',
                'arenas.id AS arenas_id',
                'linha_cuidado.nome AS linha_cuidado_nome',
                'linha_cuidado.id AS linha_cuidado_id'
            )
                ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
                ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado');

            $sql = $this->_paramsSQL($sql, $params);

            if (!empty($params['paciente'])) {
                $sql->orderBy('agendas.data', 'desc');
            } else {
                $sql->orderBy('agendas.data', 'asc');
            };
            $data = $sql->get();

            if (!count($data)) {
                throw new \Exception("Nenhum registro encontrado!");
            }

            $view->grid = $data;
            $view->params = $params;

        } catch (\Exception $e) {
            $view['error'] = $e->getMessage();
        }

        return $view;
    }

    private function _paramsSQL($sql, $params)
    {

        if ($params) {

            if (empty($params['paciente'])) {
                if (empty($params['arena'])) {
                    throw new \Exception("Selecione uma arena ou informe um parte do nome do paciente para pesquisa");
                }

                if ((!empty($params['arena']) && empty($params['data']))) {
                    throw new \Exception("Não é possivel pesquisar uma arena sem data");
                }
            } else {
                if (strlen($params['paciente']) < 4) {
                    throw new \Exception("Informe no minimo 4 letra para pequisar um paciente");
                }
            }

            if (!empty($params['paciente'])) {
                $params['paciente'] = strtoupper($params['paciente']);

                $sql->where(function ($q) use ($params) {
                    $q->where('pacientes.nome', 'LIKE', "%{$params['paciente']}%")
                        ->orWhere('pacientes.cpf', 'LIKE', "%{$params['paciente']}%")
                        ->orWhere('pacientes.cns', 'LIKE', "%{$params['paciente']}%");
                });

            }

            if (!empty($params['arena'])) {
                $sql->where('agendas.arena', '=', $params['arena']);
            }

            if (!empty($params['status'])) {
                $sql->whereIn('agendas.status', $params['status']);
            }

            if (!empty($params['linha_cuidado'])) {
                $sql->where('agendas.linha_cuidado', '=', $params['linha_cuidado']);
            }

            if (!empty($params['procedimento'])) {
                $sql->where('agendas.procedimento', '=', $params['procedimento']);
            }

            if (!empty($params['data'])) {
                $params['data'] = Util::Date2DB(urldecode($params['data']));

                $sql->whereBetween('agendas.data', array("{$params['data']} 00:00:00", "{$params['data']} 23:59:59"));
            }

            if (!empty($params['profissional'])) {
                $sql->join('atendimento', 'atendimento.agenda', '=', 'agendas.id');
                $sql->where('atendimento.medico', '=', $params['profissional']);
            }

        }

        return $sql;
    }


}
