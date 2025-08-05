<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 03/10/15
 * Time: 02:40
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PermissionRole extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_role';
    
    public static function savePermissionRole($data){
        if(!empty($data['_token']))
            unset($data['_token']);

        $data['permission_id'] = $data['permission'];
        $data['role_id'] = $data['perfil'];

        unset($data['perfil']);
        unset($data['permission']);

        $permission = PermissionRole::where('role_id', $data['role_id'])->where('permission_id', $data['permission_id'])->get()->first();

        $permission = !empty($permission->id) ? PermissionRole::find($permission->id) : new PermissionRole();
        foreach($data AS $key => $value){
            $permission->$key = $value;
        }

        $permission->save();
        Cache::flush();

        return true;
    }




}