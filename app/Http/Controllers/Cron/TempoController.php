<?php

namespace App\Http\Controllers\Cron;

use App\AtendimentoTempo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TempoController extends AbstractCron
{

    public function getIndex()
    {
        $data = $this->getRecepcaoNotInAndOut();
        if($data){
            foreach($data AS $row){
                //exit("<pre>" . print_r($row, 1) . "</pre>"); #debug-ederson
            }
        }

        $data = $this->getMedicoNotInAndOut();
        if($data){
            foreach($data AS $row){
                exit("<pre>" . print_r($row, 1) . "</pre>"); #debug-ederson
            }
        }


        exit("<pre>" . print_r($data, 1) . "</pre>"); #debug-ederson
    }


    /**
     * Pega os registros que estão prenchido a saida e não a entrada
     */
    protected function getMedicoNotInAndOut(){
        $sql = AtendimentoTempo::select('*')
            ->whereRaw('medico_in IS NULL AND medico_out IS NOT NULL')
            ->orderBy('id','DESC')
        ;

        return $sql->get()->toArray();
    }

    /**
     * Pega os registros que estão prenchido a saida e não a entrada
     */
    protected function getRecepcaoNotInAndOut(){
        $sql = AtendimentoTempo::select('*')
                ->whereRaw('recepcao_in IS NULL AND recepcao_out IS NOT NULL')
                ->orderBy('id','DESC')
        ;

        return $sql->get()->toArray();
    }

}
