<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PDOException;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class ArenaEquipamentos extends Model
{
    protected $table = 'arena_equipamentos';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() AS $key => $value) {
                $model->$key = Util::String2DB($value);
            }
        });
        Cache::flush();
    }

    public static function get($id)
    {
        $key = 'get-arena_equipamentos-' . $id;

        if (!Cache::has($key)) {
            $data = self::find($id);

            if (!empty($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getByArena($arena)
    {
        $key = 'get-arena-equipamentos-getByArena' . $arena;

        if (!Cache::has($key)) {
            $data = self::where('arena', $arena)->orderBy('nome', 'asc')->where('ativo', 1)->lists('nome', 'id')->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getAllComboByArena()
    {
        $key = 'getAllComboByArena';
        $data = [];

        if (!Cache::has($key)) {

            $sql = self::select(
                [
                    'arenas.nome AS arena',
                    'arena_equipamentos.id',
                    'arena_equipamentos.nome AS equipamento',
                ]
            )
                ->join('arenas', 'arenas.id', '=', 'arena_equipamentos.arena')
                ->where('arenas.ativo', 1)
                ->where('arena_equipamentos.ativo', 1)
                ->orderBy('arenas.nome', 'asc')
                ->orderBy('arena_equipamentos.nome', 'asc')
                ->get();

            if (!empty($sql[0])) {
                foreach ($sql AS $row) {
                    $data[$row->arena][$row->id] = $row['equipamento'];
                }
            }

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public function saveData($data)
    {
        try {
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            $model = empty($data['id']) ? new ArenaEquipamentos() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            return true;
        } catch (PDOException $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            return false;
        }
    }

}
