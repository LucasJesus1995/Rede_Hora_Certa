<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class SyncsDB extends Model
{
    protected $table = 'syncs_dbs';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {


        });
    }

    public static function syncsDB($table, $table_id, $type){

    }

    public static function syncsDBProccessed($id){

    }

}
