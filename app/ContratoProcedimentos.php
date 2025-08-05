<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;
use PDOException;

class ContratoProcedimentos extends Model
{
    protected $table = 'contrato_procedimentos';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            foreach ($model->getAttributes() AS $key => $value) {
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo()
    {
        return self::lists('nome', 'id')->toArray();
    }

    public static function getContratoProcedimentoByContratoLote($contrato = 2, $lote = 7)
    {
        $key = "getContratoProcedimentoByContratoLote-{$contrato}-{$lote}";;

        if (!Cache::has($key)) {
            $data = [];
            $contrato_procedimento = ContratoProcedimentos::where('contrato', $contrato)->where('lote', $lote)->get();

            if (!empty($contrato_procedimento[0])) {
                foreach ($contrato_procedimento AS $row) {
                    $data[$row->procedimento] = $row->valor_unitario;
                }

                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        asort($data);
        return $data;


        return count($contrato_procedimento) ? $contrato_procedimento[0] : null;
    }

    public static function getContratoProcedimentoByContratoProcedimentoLote($contrato, $procedimento, $lote)
    {
        $contrato_procedimento = ContratoProcedimentos::where('contrato', $contrato)->where('procedimento', $procedimento)->where('lote', $lote)->get();

        return count($contrato_procedimento) ? $contrato_procedimento[0] : null;
    }

    public static function getContratoProcedimentoByContratoProcedimento($procedimentos, $lote = 7)
    {
        $contrato_procedimento = ContratoProcedimentos::where('lote', $lote)->whereIn('procedimento', $procedimentos)->get();

        return count($contrato_procedimento) > 0 ? $contrato_procedimento : null;
    }

    public function saveData($data)
    {

        try {
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            $contrato_procedimento = self::getContratoProcedimentoByContratoProcedimentoLote($data['contrato'], $data['procedimento'], $data['lote']);

            if (!count($contrato_procedimento)) {
                $contrato_procedimento = new ContratoProcedimentos();
                $contrato_procedimento->contrato = $data['contrato'];
                $contrato_procedimento->procedimento = $data['procedimento'];
                $contrato_procedimento->lote = $data['lote'];
            }

            $contrato_procedimento->quantidade = intval($data['quantidade']) > 0 ? $data['quantidade'] : 1;
            $contrato_procedimento->demanda = $data['demanda'];
            $contrato_procedimento->valor_unitario = str_replace(" ", "", $data['valor_unitario']);
            $contrato_procedimento->save();

            return true;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return false;
    }

}
