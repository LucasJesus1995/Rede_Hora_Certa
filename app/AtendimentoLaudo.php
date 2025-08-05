<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AtendimentoLaudo extends Model{
    protected $table = 'atendimento_laudo';

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {

        });

        static::deleting(function ($model){

        });

    }

    public static function get($id)
    {
        $key = 'get-atendimento-laudo-'.$id;

        if (!Cache::has($key)) {
            $data = AtendimentoLaudo::find($id);

            if (!empty($data->id))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public function cids()
    {
        return $this->belongsTo('App\Cid', 'cid');
    }


    public static function getBiopsia($id)
    {
        return  AtendimentoLaudo::where('atendimento', $id)->where('resultado', 3)->orderBy('id','desc')->get()->toArray();
    }

    public static function getLaudoData($arena = null, $linha_cuidado = null, $ano = null, $mes = null, $medico = null, $resultado = array(1,2,3))
    {
        $date = Util::periodoMesPorAnoMes($ano, $mes);

        $sql = AtendimentoLaudo::select(
            "atendimento_laudo.id AS laudo",
            "atendimento_laudo.biopsia",
            "atendimento_laudo.status_biopsia",
            "atendimento_laudo.resultado",
            "atendimento.id AS atendimento",
            "agendas.data AS data",
            "arenas.nome AS arena_nome",
            DB::raw("trim(pacientes.nome) AS nome"),
            "pacientes.id AS paciente_id",
            "pacientes.cns",
            "pacientes.celular",
            "pacientes.telefone_comercial",
            "pacientes.telefone_residencial",
            "pacientes.nascimento",
            "linha_cuidado.nome AS linha_cuidado",
            "profissionais.nome AS profissional",
            "estabelecimento.nome AS estabelecimento",
            "procedimentos.nome AS procedimento"
        )
            ->join('atendimento','atendimento.id','=','atendimento_laudo.atendimento')
            ->join('agendas','agendas.id','=','atendimento.agenda')
            ->join('arenas','agendas.arena','=','arenas.id')
            ->join('pacientes','pacientes.id','=','agendas.paciente')
            ->join('linha_cuidado','linha_cuidado.id','=','agendas.linha_cuidado')
            ->join('profissionais','profissionais.id','=','atendimento.medico')
            ->join('estabelecimento','estabelecimento.id','=','agendas.estabelecimento')
            ->leftJoin('procedimentos', 'procedimentos.id', '=', 'agendas.procedimento')
            ->whereIn('agendas.status', [2,6,8,10,98,99])
            ->whereIn('atendimento_laudo.resultado', $resultado)
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
        ;

        if(!empty($linha_cuidado)){
            $sql->where('linha_cuidado.id', '=', $linha_cuidado);
        }

        if(!empty($arena)){
            $sql->where('agendas.arena', '=', $arena);
        }

        if(!empty($medico)){
            $sql->where('profissionais.id', '=', $medico);
        }

        return $sql->get();
    }

}
