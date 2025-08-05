<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use PDOException;

class LaudoMedico extends Model
{
    protected $table = 'laudo_medico';

	public static function boot() {
        parent::boot();

        static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                if(!in_array($key, ['descricao']))
                    $model->$key = Util::String2DB($value);

                if($key == 'descricao'){
                    $model->$key = urlencode($value);
                }
            }
        });
    }

    public static function getNomeLaudo($id)
    {
        $laudo = LaudoMedico::find($id);

        return !empty($laudo->nome) ? $laudo->nome : null;
    }

    public static function getLaudoDescricao($id)
    {
        $laudo = LaudoMedico::find($id);

        return !empty($laudo->descricao) ? $laudo->descricao : null;
    }

    public function saveData($data) {
        try {
            if (isset($data['_token']))
                unset($data['_token']);

            $model = empty($data['id']) ? new LaudoMedico() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }

            $model->save();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function ByLinhaCuidado($linha_cuidado, $medico = null){
        $data = [];




        $sql =  LaudoMedico::select(
                [
                    'laudo_medico.id',
                    'laudo_medico.nome',
                    'laudo_medico.medico',
                    'profissionais.nome AS medico_nome',
                ]
            )
            ->leftJoin('profissionais','profissionais.id','=','laudo_medico.medico')
            ->where('laudo_medico.linha_cuidado', $linha_cuidado)
            ->orderBy('laudo_medico.nome', 'ASC')
        ;

        if($medico){
            $is_laudos = LaudoMedico::where('medico', $medico)->where('laudo_medico.linha_cuidado', $linha_cuidado)->limit(1)->get();
            if(count($is_laudos))
                $sql->where('medico', $medico);
            else
                $sql->whereNull('medico');
        }

        return $sql->get()->toArray();
    }

    public static function ByLinhaCuidadoMedico($linha_cuidado, $medico = null){
        $data = [];

        $key = 'get-laudos-medidos-'.$linha_cuidado."-".$medico;

        if (!Cache::has($key)) {
            $laudos = self::ByLinhaCuidado($linha_cuidado, $medico);

            if(!empty($laudos)){
                foreach ($laudos AS $laudo){
                    $medico = !empty($laudo['medico_nome']) ? trim($laudo['medico_nome']) : "LAUDOS GERAIS";
                    $data[$medico][] = $laudo;
                }
            }

            asort($data);

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
