<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AtendimentoTempo extends Model
{
    protected $table = 'atendimento_tempo';
    protected static  $key = 'get_atendimento_tempo';

    public static function getByAtendimento($atendimento, $force = true){
        $key = self::$key.$atendimento;
        $data = null;

        if (!Cache::has($key)) {
            $res = AtendimentoTempo::where('atendimento', $atendimento)->get();

            if (!empty($res) && !empty(@$res[0]->id)) {
                Cache::put($key, $res[0], CACHE_DAY);
                $data = $res[0];
            }else{
                $tempo = new AtendimentoTempo();
                $tempo->atendimento = $atendimento;
                $tempo->save();

                if($force){
                    return AtendimentoTempo::getByAtendimento($atendimento, false);
                }
            }

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function removeCache($atendimento){
        $key = self::$key.$atendimento;

        Cache::pull($key);
    }

    public static function recepcaoIN($atendimento){
        $tempo = AtendimentoTempo::getByAtendimento($atendimento);

        if(!empty($tempo)){
            if(empty($tempo->recepcao_in)){
                $tempo->recepcao_in = date('Y-m-d H:i:s');
                $tempo->save();

                self::removeCache($atendimento);
            }
        }
    }


    public static function recepcaoOUT($atendimento){
        $tempo = AtendimentoTempo::getByAtendimento($atendimento);

        if(!empty($tempo)){
            if(empty($tempo->recepcao_out)){
                $tempo->recepcao_out = date('Y-m-d H:i:s');
                $tempo->save();

                if(empty($tempo->recepcao_in)){
                    $_atendimento = Atendimentos::get($atendimento);

                    $tempo->recepcao_in = $_atendimento['created_at'];
                    $tempo->save();
                }

                self::removeCache($atendimento);
            }
        }

    }

    public static function medicinaIN($atendimento){
        $tempo = AtendimentoTempo::getByAtendimento($atendimento);

        if(!empty($tempo)){
            if(empty($tempo->medico_in)){
                $tempo->medico_in = date('Y-m-d H:i:s');
                $tempo->save();

                self::removeCache($atendimento);
            }
        }
    }

    public static function medicinaOUT($atendimento){
        $tempo = AtendimentoTempo::getByAtendimento($atendimento);

        if(!empty($tempo)){

            if(empty($tempo->medico_out)){
                $tempo->medico_out = date('Y-m-d H:i:s');
                $tempo->save();

                self::medicoOutInUpdate($tempo);

                self::removeCache($atendimento);
            }
        }

    }

    public static function getRelatorio($params)
    {
        $r_data = array();

        $sql =  AtendimentoTempo::select(
            [
                'agendas.id AS agenda',
                'agendas.data',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'atendimento.preferencial',
                'atendimento_tempo.id',
                'atendimento_tempo.atendimento',
                'atendimento_tempo.recepcao_in',
                'atendimento_tempo.recepcao_out',
                'atendimento_tempo.medico_in',
                'atendimento_tempo.medico_out',
                'atendimento_tempo.created_at'
            ]
        )
            ->join('atendimento','atendimento.id',  '=', 'atendimento_tempo.atendimento')
            ->join('agendas','agendas.id',  '=', 'atendimento.agenda')
            ->join('arenas','arenas.id',  '=', 'agendas.arena')
            ->join('linha_cuidado','linha_cuidado.id',  '=', 'agendas.linha_cuidado')
            ->where('atendimento.etapa', '>', 0)
            ->where('agendas.ativo', 1)
            ->orderBy('arena','ASC')
            ->orderBy('data','DESC')
        ;

        if($params['arena']){
            $sql->where('arenas.id', $params['arena']);
        }  
        
        if($params['linha_cuidado']){
            $sql->where('linha_cuidado.id', $params['linha_cuidado']);
        }

        if($params['data_inicial'] && $params['data_final']){
            $sql->where('agendas.data', '>=', Util::Date2DB($params['data_inicial']).' 00:00:00');
            $sql->where('agendas.data', '<=', Util::Date2DB($params['data_final']).' 23:59:59');
            $sql->where('agendas.data', '!=', "0000-00-00 00:00:00");
        }

        $data = $sql->get()
            ->toArray();


        if($data) {
            foreach ($data AS $row) {
                $r_data[$row['arena']][\App\Http\Helpers\Util::DBTimestamp2UserDate2($row['data'])][] = $row;
            }
        }

        return $r_data;
    }

    protected  static function medicoOutInUpdate($tempo){
        $medico_in = null;

        $medicamento = AtendimentoMedicamento::select('created_at')
            ->where('created_at','<=', $tempo->medico_out)
            ->where('atendimento', $tempo->atendimento)
            ->limit(1)
            ->orderBy('id','DESC')
            ->get()
            ->toArray();
        
        if(!empty($medicamento[0]['created_at'])){
            $medico_in = $medicamento[0]['created_at'];
            
            $procedimento = AtendimentoProcedimentos::select('created_at')
                ->where('created_at','<=', $tempo->medico_out)
                ->where('created_at', '>=', $medico_in)
                ->where('atendimento', $tempo->atendimento)
                ->limit(1)
                ->orderBy('id','DESC')
                ->get()
                ->toArray();

            if(!empty($procedimento[0]['created_at'])){
                $medico_in = $procedimento[0]['created_at'];
            }

            if(!empty($medico_in)){
                $tempo->medico_in = $medico_in;
                $tempo->save();
            }

        }
    }

}
