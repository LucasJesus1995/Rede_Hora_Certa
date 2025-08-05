<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Lotes extends Model{

    protected $table = 'lotes';
    
    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static  function Combo(){
        return self::where('ativo', 1)->lists('nome','id')->toArray();
    }

    public static function getProfissionaisCBO($lote)
    {
        $medicos = LoteProfissional::getByLote($lote);

        $data = [];
        if($medicos){
            foreach ($medicos AS $medico){
                $cbos_medico = LoteProfissionalCbo::select("cbo.codigo")
                    ->where('lote_profissional', $medico->lote_profissionais_id)
                    ->join('cbo','cbo.id','=','lote_profissional_cbos.cbo')
                    ->get();

                foreach ($cbos_medico AS $cbo){
                    if(!empty($medico->profissionais_cns)) {
                        $data[$cbo->codigo][$medico->profissionais_cns] = $medico->profissionais_cns;
                    }
                }
            }
        }

        return $data;
    }

    public static function getArenas($lote)
    {
        $arenas = self::select([
                'lotes_arena.arena',
            ])
            ->join('lotes_arena','lotes_arena.lote','=','lotes.id')
            ->whereIn('lotes_arena.lote', $lote)
            ->lists('arena')->toArray();

        return count($arenas) ? $arenas : null;
    }

    public function saveData($data)
    {
        try {

            if (is_array($data)) {
                $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($this->getTable());
                $model = empty($data['id']) ? new Lotes() : $this->find($data['id']);
                foreach ($columns AS $col) {
                    if (array_key_exists($col, $data))
                        $model->$col = $data[$col];
                }

                $model->save();
            }

            if (!empty($model->id)) {

            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getContratoByLoteId($lote)
    {
        $data = null;
        $key = 'contrato-lote' . $lote;

        if (!Cache::has($key)) {
            $data = self::where('id', $lote)->get();

            if (!empty($data[0])){
                $data = $data[0];
                Cache::put($key, $data, CACHE_DAY);

            }

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
