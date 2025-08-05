<?php

namespace App\Http\Controllers;

use App\Faturamento;
use App\LotesLinhaCuidado;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PainelController extends Controller
{

    public function __construct() {
        $this->title = "app.painel";

        parent::__construct();
    }

    public function getIndex()
    {
        return redirect("admin/agendas");

        $view = View("admin.painel.index")->with('title', $this->title);


        return $view;
    }

    public function getDashboard(){
        $view = View("admin.painel.dashboard")->with('title', 'Painel de Controle');

        $res_faturamento = Faturamento::select(
                [
                    'lotes.nome AS lote_nome',
                    'lotes.id AS lote_id',
                    'linha_cuidado.nome AS linha_cuidado_nome',
                    'linha_cuidado.id AS linha_cuidado_id',
                    DB::raw('count(1) as faturado')
                ]
            )
            ->join('faturamento_procedimentos', 'faturamento_procedimentos.faturamento','=','faturamento.id')
            ->join('lotes', 'lotes.id','=','faturamento_procedimentos.lote')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id','=','faturamento_procedimentos.atendimento_procedimento')
            ->join('atendimento','atendimento.id','=','atendimento_procedimentos.atendimento')
            ->join('agendas','atendimento.agenda','=','agendas.id')
            ->join('linha_cuidado','linha_cuidado.id','=','agendas.linha_cuidado')
            ->groupBy(
                [
                    'lotes.nome',
                    'lotes.id',
                    'linha_cuidado.nome',
                    'linha_cuidado.id',
                ]
            )
            ->get();

        $data = [];
        foreach ($res_faturamento AS $row){

            $res_maximo = LotesLinhaCuidado::select('maximo')
                ->where('linha_cuidado','=',$row->linha_cuidado_id)
                ->where('lote','=',$row->lote_id)
                ->get()
                ->toArray();

            $data[$row->lote_nome][] = [
                'linha_cuidado' => $row->linha_cuidado_nome,
                'faturado' => $row->faturado,
                'maximo' => !empty($res_maximo[0]['maximo']) ? $res_maximo[0]['maximo'] : 0,
            ];
        }

        $chart = Charts::multi('bar', 'fusioncharts')
            ->title("Lotes (Faturamento)")
            ->dimensions(0, 400)
            ->template("material")
//            ->dataset('Element 1', [5,20,100])
//            ->dataset('Element 2', [15,30,80])
//            ->dataset('Element 3', [25,10,40])
//            ->labels(['One', 'Two', 'Three']);
        ;

        $i = 0;
        foreach ($data AS $key => $rows) {
           //$chart->labels[$i] = $key;

            if(count($rows)){
                foreach ($rows AS $row){
                    $chart->dataset($row['linha_cuidado'],  $row['faturado']);
                    $chart->labels($key);
                }
            }

            $i++;
        }

       // exit("<pre>LINE: ".__LINE__." - ".print_r($chart, 1)."</pre>"); #debug-edersonsandre


        $view->chart = $chart;

       // exit("<pre>LINE: ".__LINE__." - ".print_r($chart, 1)."</pre>"); #debug-edersonsandre
die;
        return $view;
    }

}
