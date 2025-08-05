<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;

class Medicamentos extends Model
{
    protected $table = 'medicamentos';

	public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }


    public static function get($id){
        $key = 'get-medicamento-'.$id;

        if (!Cache::has($key)) {
            $data = Medicamentos::find($id)->toArray();

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }


    public static function Combo(){
        return self::lists('nome','id')->toArray();
    }

	public function saveData($data) {
        try {
            if (isset($data['_token']))
                unset($data['_token']);

            $linha_cuidado = isset($data['linha_cuidado']) ? $data['linha_cuidado'] : null;
            unset($data['linha_cuidado']);

            $model = empty($data['id']) ? new Medicamentos() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            if (!empty($model->id) && $linha_cuidado) {
                $_old_medicamentos = LinhaCuidadoMedicamentos::where('medicamento', '=', $model->id)->get()->toArray();

                LinhaCuidadoMedicamentos::where('medicamento', '=', $model->id)->delete();
                foreach ($linha_cuidado AS $row) {
                    $_model = new LinhaCuidadoMedicamentos();
                    $_model->medicamento = $model->id;
                    $_model->linha_cuidado = $row;
                    $_model->save();
                }

                if($_old_medicamentos){
                    foreach($_old_medicamentos AS $row){
                        $medicamento_linha_cuidado = LinhaCuidadoMedicamentos::where('linha_cuidado', '=', $row['linha_cuidado'])->where('medicamento', '=', $row['medicamento'])->get()->toArray();
                        if(!empty($medicamento_linha_cuidado[0]['id'])){
                            $_medicamento = LinhaCuidadoMedicamentos::find($medicamento_linha_cuidado[0]['id']);
                            $_medicamento->default = $row['default'];
                            $_medicamento->valor = $row['valor'];
                            $_medicamento->save();
                        }
                    }
                }
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static  function ByLinhaCuidado($linha_cuidado){
        $key = 'get-medimento-b-linha-cuidado-'.$linha_cuidado;

        $data = [];

        if (!Cache::has($key)) {
            $_res =  Medicamentos::select(
                [
                    'medicamentos.id',
                    'medicamentos.nome'
                ]
            )
                ->join('linha_cuidado_medicamentos','linha_cuidado_medicamentos.medicamento',  '=', 'medicamentos.id')
                ->where('linha_cuidado_medicamentos.linha_cuidado', $linha_cuidado)
                ->orderBy('medicamentos.nome', 'ASC')
                ->get()
                ->toArray()
            ;

            if($_res){
                foreach($_res AS $row){
                    $data[$row['id']] = $row['nome'];
                }
            }

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

}
