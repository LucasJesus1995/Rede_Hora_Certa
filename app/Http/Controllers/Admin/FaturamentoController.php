<?php

namespace App\Http\Controllers\Admin;

use App\Agendas;
use App\Arenas;
use App\Faturamento;
use App\FaturamentoLotes;
use App\FaturamentoProcedimento;
use App\Http\Controllers\TraitController;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\LinhaCuidadoProcedimentos;
use App\Lotes;
use App\LotesLinhaCuidado;
use App\Procedimentos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Zend\Filter\Digits;

class FaturamentoController extends Controller
{
    public $model = 'Faturamento';

    use TraitController;

    public function __construct() {
        $this->title = "app.faturamento";

        parent::__construct();
    }

    public function postIndex(Requests\FaturamentoRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

    public function postFaturamentoLote(){
        $view = View("admin/{$this->layout}/faturamento-lote");
        $data = Input::all();

        $view->faturamento = $faturamento = Faturamento::find($data['faturamento']);
        $view->lotes = $lotes =  Lotes::Combo();

        foreach ($lotes AS $codigo_lote => $lote){
            $faturamento_lotes[] = FaturamentoLotes::saveData($faturamento->id, $codigo_lote);
        }

        return $view;
    }

    public function postLoteLinhaCuidadoCadastro(){
        $data = Input::all();

        try{
            $lote_linha_cuidado = LotesLinhaCuidado::getByFaturamentoLoteLinhaCuidado($data['faturamento_lote'], $data['linha_cuidado']);

            $_filter = new Digits();

            if(!empty($lote_linha_cuidado)){
                $_lote_linha_cuidado = LotesLinhaCuidado::find($lote_linha_cuidado->id);
                $_lote_linha_cuidado->$data['key'] = $_filter->filter($data['value']);
                $_lote_linha_cuidado->save();
            }else{
                $_lote_linha_cuidado = new LotesLinhaCuidado();
                $_lote_linha_cuidado->$data['key'] = $_filter->filter($data['value']);
                $_lote_linha_cuidado->linha_cuidado = $data['linha_cuidado'];
                $_lote_linha_cuidado->faturamento_lote = $data['faturamento_lote'];
                $_lote_linha_cuidado->save();
            }

            $return['success'] = true;

        }catch (\Exception $e){
            $return['success'] = false;
        }

        return json_encode($return);
    }

    public function postAtivar(){
        $data = Input::all();

        DB::transaction(function () use($data) {
            $return = [];
            try{
                Faturamento::where('status', 2)
                    ->update(['status' => 3]);

                Faturamento::where('id', $data['faturamento'])
                    ->update(['status' => 2]);

                DB::commit();

                $return['success'] = true;
                $return['message'] = "Faturamento ativo com sucesso!";
            }catch (\Exception $e){
                $return['success'] = false;
                $return['message'] = $e->getMessage();

                DB::rollBack();
            }

            return exit(json_encode($return));
        });

    }

    public function postFechar(){
        $data = Input::all();

        DB::transaction(function () use($data) {
            $return = [];
            try{

                Faturamento::where('id', $data['faturamento'])
                    ->update(['status' => 3]);

                DB::commit();

                $return['success'] = true;
                $return['message'] = "Faturamento finalizado com sucesso!";
            }catch (\Exception $e){
                $return['success'] = false;
                $return['message'] = $e->getMessage();

                DB::rollBack();
            }

            return exit(json_encode($return));
        });

    }

}
