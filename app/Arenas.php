<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PDOException;
use Zend\Filter\Digits;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class Arenas extends Model
{
    protected $table = 'arenas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                $model->$key = Util::String2DB($value);
            }

            $digits = new Digits();

            $model->celular = $digits->filter($model->celular);
            $model->telefone = $digits->filter($model->telefone);
        });
        Cache::flush();
    }

    public static function Combo()
    {
        $key = 'arenas-combo';

        if (!Cache::has($key)) {
            $data = [];

            $res = self::where('arenas.ativo', 1)->select(['arenas.nome', 'arenas.id', 'lotes.nome as lote'])
                ->join('lotes_arena', 'lotes_arena.arena', '=', 'arenas.id')
                ->join('lotes', 'lotes.id', '=', 'lotes_arena.lote')
                ->get();

            if (count($res)) {
                foreach ($res as $row) {
                    $data[$row->lote][$row->id] = $row->nome;
                }
            }

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);

        } else {
            $data = Cache::get($key);
        }

        asort($data);
        return $data;
    }

    public static function ComboContrato()
    {
        $data = [];
        $_data = self::Combo();
        foreach ($_data as $contrato => $arenas) {
            foreach ($arenas as $k => $arena) {
                $data[$k] = "{$contrato} - {$arena}";
            }
        }

        return $data;
    }

    public static function getComboCirurgico()
    {
        $key = 'arenas-combo-cirurgicos' . \App\User::getId();
        $data = [];

        if (!Cache::has($key)) {
            $data = self::whereIn('arenas.id', self::getByLote(\App\User::getLote()))
                ->join('arenas_linha_cuidado', 'arenas_linha_cuidado.arena', '=', 'arenas.id')
                ->join('linha_cuidado', 'arenas_linha_cuidado.linha_cuidado', '=', 'linha_cuidado.id')
                ->where('linha_cuidado.especialidade', 2)
                ->where('arenas.ativo', 1)
                ->lists('arenas.nome', 'arenas.id')
                ->toArray();

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);

        } else {
            $data = Cache::get($key);
        }

        asort($data);
        return $data;
    }

    public static function get($id)
    {
        $key = 'get-arenas-' . $id;

        if (!Cache::has($key)) {
            $data = Arenas::find($id)->toArray();

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getByLote($lote)
    {
        $key = 'lote-user' . \App\User::getLote();

        if (!Cache::has($key)) {
            $lote_arena = LotesArena::select('arenas.id')
                ->join('arenas', 'arenas.id', '=', 'lotes_arena.arena')
                ->where('lote', $lote)
                ->where('arenas.ativo', 1)
                ->get()->toArray();

            $arenas = [];
            if ($lote_arena) {
                foreach ($lote_arena as $row) {
                    $arenas[] = $row['id'];
                }
            }

            if (count($arenas))
                Cache::put($key, $arenas, CACHE_DAY);
        } else {
            $arenas = Cache::get($key);
        }

        return $arenas;
    }

    public function saveData($data)
    {
        try {
            if (isset($data['_token']))
                unset($data['_token']);

            $linha_cuidado = $data['linha_cuidado'];
            unset($data['linha_cuidado']);

            $unidade[] = $data['unidade'];
            unset($data['unidade']);

            $model = empty($data['id']) ? new Arenas() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            if (!empty($model->id)) {
                ArenasLinhaCuidado::where('arena', '=', $model->id)->delete();

                foreach ($linha_cuidado as $row) {
                    $_linha_cuidado = new ArenasLinhaCuidado();
                    $_linha_cuidado->arena = $model->id;
                    $_linha_cuidado->linha_cuidado = $row;
                    $_linha_cuidado->save();
                }

                UnidadesArenas::where('arena', '=', $model->id)->delete();
                foreach ($unidade as $row) {
                    $_unidade = new UnidadesArenas();
                    $_unidade->arena = $model->id;
                    $_unidade->unidade = $row;
                    $_unidade->save();
                }
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getLinhasCuidado($arena, $full = false)
    {
        return LinhaCuidado::ByArena($arena, $full);
    }

}
