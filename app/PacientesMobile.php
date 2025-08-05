<?php

namespace App;

use App\Http\Helpers\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use PDOException;
use Zend\Filter\Digits;
use Illuminate\Support\Facades\Cache;

class PacientesMobile extends Model{

    protected $table = 'pacientes_mobile';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

            $carbon = Carbon::now();

            $model->token = PacientesMobile::generateToken($model->paciente);
            $model->validade = $carbon->addMonth(3);
        });

        Cache::flush();
    }

    private static function generateToken($paciente)
    {
        return md5(sha1(sha1($paciente).sha1(date('YmdHis')).sha1($paciente).sha1(date('YmdHis')).sha1($paciente)));
    }

    public static function __save($paciente)
    {
        $paciente_mobile = PacientesMobile::getByPaciente($paciente->id);

        $_paciente_mobile = ($paciente_mobile) ? $paciente_mobile:  new PacientesMobile();
        $_paciente_mobile->paciente = $paciente->id;
        $_paciente_mobile->save();

        return $_paciente_mobile;
    }

    public static function getByPaciente($paciente)
    {
        $_paciente = self::select('*')->where('paciente', $paciente)->get();

        return (!count($_paciente)) ? false : $_paciente['0'];
    }

    public static function getByCNSEToken($key, $token)
    {
        $_paciente = Pacientes::select('pacientes.*')
            ->join('pacientes_mobile','pacientes_mobile.paciente','=','pacientes.id')
            ->where('pacientes.cns', $key)
            ->where('pacientes_mobile.token', $token)
            ->get();

        return count($_paciente) ? $_paciente[0] : null;
    }


}
