<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use PDOException;

class Contratos extends Model
{
	protected $table = 'contratos';
    
    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo(){
        return self::lists('nome','id')->toArray();
    }

    public function saveData($data) {
        try {
            if (isset($data['_token']))
                unset($data['_token']);

            $lotes = $data['lotes'];
            unset($data['lotes']);

            $model = empty($data['id']) ? new Contratos() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            if (!empty($model->id)) {
                ContratoLotes::where('contrato', '=', $model->id)->delete();
                foreach ($lotes AS $row) {
                    $_model = new ContratoLotes();
                    $_model->contrato = $model->id;
                    $_model->lote = $row;
                    $_model->save();
                }
            }

            return true;
        } catch (PDOException $e) {

            return false;
        }
    }

}
