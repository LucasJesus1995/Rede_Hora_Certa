<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 04/09/18
 * Time: 16:43
 */

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;

class SubGrupos extends Model
{
    protected $table = 'procedimento_sub_grupos';

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
        $data = [];

        $sql = Grupos::select(
            [
                'procedimento_grupos.descricao as grupo',
                'procedimento_grupos.codigo as grupo_codigo',
                'procedimento_sub_grupos.codigo as sub_grupo_codigo',
                'procedimento_sub_grupos.descricao as sub_grupo_descricao',
                'procedimento_sub_grupos.id as sub_grupo_id',
            ]
        )
            ->join('procedimento_sub_grupos', 'procedimento_sub_grupos.grupo', '=', 'procedimento_grupos.id')
            ->orderBy('procedimento_sub_grupos.descricao', 'asc')
            ->get();

        foreach ($sql as $row) {
            $data["{$row->grupo_codigo} - {$row->grupo}"][$row->sub_grupo_id] = "{$row->sub_grupo_codigo} - {$row->sub_grupo_descricao}";
        }

        return $data;
    }

    public static function getAll($sub_grupo = null)
    {
        $sql = \App\SubGrupos::select(
            [
                'procedimento_sub_grupos.codigo',
                'procedimento_sub_grupos.descricao',
                'procedimento_grupos.codigo AS grupo_codigo',
                'procedimento_grupos.descricao AS grupo_descricao',

            ]
        )
            ->join('procedimento_grupos', 'procedimento_grupos.id', '=', 'procedimento_sub_grupos.grupo')
            ->orderBy('procedimento_grupos.codigo', 'asc')
            ->orderBy('procedimento_sub_grupos.codigo', 'asc')
            //->limit(7)
        ;

        if (!is_null($sub_grupo) && is_array($sub_grupo)) {
            $sql->whereIn('procedimento_sub_grupos.id', $sub_grupo);
        }

        return $sql->get();
    }

}
