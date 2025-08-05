<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Zend\Filter\Digits;
use App\Http\Helpers\Util;

class Profissionais extends Model
{
    protected $table = 'profissionais';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

            $digits = new Digits();

            $model->cpf = $digits->filter($model->cpf);
        });
    }

    public static function getMedicoByID($medico)
    {
        $key = 'get-medicoByID-'.$medico;

        if (!Cache::has($key)) {
            $data = self::where('id',$medico)->where('type', 1)->get();

            if (count($data)) {
                $data = $data[0];

                Cache::put($key, $data, CACHE_DAY);
            }
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public function saveData($data) {
        try {

            if (isset($data['_token']))
                unset($data['_token']);

            $linha_cuidado = !empty($data['linha_cuidado']) ? $data['linha_cuidado'] : [];
            unset($data['linha_cuidado']);

            $cbo = !empty($data['cbo']) ? $data['cbo'] : [];
            unset($data['cbo']);

            $arena = !empty($data['arena']) ? $data['arena'] : [];
            unset($data['arena']);

            $model = empty($data['id']) ? new Profissionais() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            if (!empty($model->id)) {

                ProfissionaisLinhaCuidado::where('profissional', '=', $model->id)->delete();
                foreach ($linha_cuidado AS $row) {
                    $_model = new ProfissionaisLinhaCuidado();
                    $_model->profissional = $model->id;
                    $_model->linha_cuidado = $row;
                    $_model->save();
                }

                ProfissionaisCbo::where('profissional', '=', $model->id)->delete();
                foreach ($cbo AS $row) {
                    $_model = new ProfissionaisCbo();
                    $_model->profissional = $model->id;
                    $_model->cbo = $row;
                    $_model->save();
                }

                ProfissionaisArenas::where('profissional', '=', $model->id)->delete();
                foreach ($arena AS $row) {
                    $_model = new ProfissionaisArenas();
                    $_model->profissional = $model->id;
                    $_model->arena = $row;
                    $_model->save();
                }

            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function Combo($type = null){
        $data = Profissionais::select('id','nome','type')->orderBy('nome','ASC')->get()->toArray();
        $res = array();
        foreach($data AS $row){
            $perfil = Util::TypeProfissional($row['type']);

            if(!$type)
                $res[$perfil][$row['id']] = trim($row['nome']);
            else {
                if($type == $row['type'])
                    $res[$row['id']] = trim($row['nome']);
            }
        }

        return $res;
    }

    public static function ComboByLinhaCuidado($linha_cuidado){
        $key = 'get-medicos-por-linha-cuidado-'.$linha_cuidado;
        $data = [];

        $_data = Profissionais::select(
                [
                    'profissionais.nome',
                    'profissionais.cro',
                    'profissionais.id'
                ]
            )
            ->join('profissionais_linha_cuidado','profissionais_linha_cuidado.profissional','=','profissionais.id')
            ->where('profissionais_linha_cuidado.linha_cuidado', $linha_cuidado)
            ->where('profissionais.type', 1)
            ->where('profissionais.ativo', 1)
            ->orderBy('profissionais.nome', 'asc')->get();

        if (!Cache::has($key)) {

            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->id] = Util::StrPadLeft($row->cro, 6) ." - {$row->nome}";
                }
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function ComboMedicos(){
        return Util::Medicos();
    }

    public static function ByArena($id, $type = 1){
        $data = [];

         $res = self::where('profissionais_arenas.arena', $id)
             ->where('profissionais.type', $type)
             ->select(['profissionais.id','profissionais.nome','profissionais.type'])
             ->join('profissionais_arenas','profissionais_arenas.profissional' , '=', 'profissionais.id')
             ->get();

        $data = [];
        foreach($res AS $row){
            $data[$row->id] = $row->nome;
        }

        return $data;
    }
}
