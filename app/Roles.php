<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 03/10/15
 * Time: 02:36
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    public static function Combo(){
        return self::where('ativo',true)->lists('role_title','id')->toArray();
    }

    public static  function getRolesPermission($role){

        $roles = Permission::where('permission', null)
            ->select(['permissions.id', 'permissions.permission_title', 'permissions.permission_slug',  'permissions.internal', 'permission_role.view','permission_role.created','permission_role.delete','permission_role.list'])
            ->leftJoin('permission_role','permission_role.permission_id','=', 'permissions.id')
            ->orderBy('permissions.ordem','asc')
            ->get();
        $rules = array();

        if($roles){
            foreach($roles AS $row){
                $roles_child = Permission::where('permission', $row->id)
                   ->select(['permissions.id', 'permissions.permission_title', 'permissions.permission_slug', 'permissions.internal', 'permission_role.view','permission_role.created','permission_role.delete','permission_role.list'])
                    //->leftJoin('permission_role','permission_role.permission_id','=', 'permissions.id')
                        ->leftJoin('permission_role', function($leftJoin) use($role)
                        {
                            $leftJoin->on('permission_role.permission_id', '=', 'permissions.id')
                                ->where('permission_role.role_id', '=', $role);


                        })
                    ->orderBy('permissions.ordem','asc')
                    ->get();
                

               // if($roles_child){
                    $rules[$row->id] = array(
                        'id' => $row->id,
                        'title' => $row->permission_title,
                    );

                    $rules[$row->id]['roles'][$row->id] = array(
                        'id' => $row->id,
                        'title' => $row->permission_title,
                        'slug' => $row->permission_slug,
                        'view' => (boolean) $row->view,
                        'list' => (boolean) $row->list,
                        'created' => (boolean) $row->created,
                        'delete' => (boolean) $row->delete,
                        'internal' =>  $row->internal,
                    );

                    foreach($roles_child AS $row_child){
                        $rules[$row->id]['roles'][$row_child->id] = array(
                            'id' => $row_child->id,
                            'title' => $row_child->permission_title,
                            'slug' => $row_child->permission_slug,
                            'view' => (boolean) $row_child->view,
                            'list' => (boolean) $row_child->list,
                            'created' => (boolean) $row_child->created,
                            'delete' => (boolean) $row_child->delete,
                            'internal' => $row_child->internal,
                        );
                    }
               // }

            }
        }

        return $rules;
    }

    public static function getBySlug($slug){
        $key = 'get-permission-slug-'.$slug;

        if (!Cache::has($key)) {
            $data = Permission::where('permission_slug', $slug)->get()->toArray();

            $data = !empty($data[0]) ? $data[0] : null;

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getRoleByPermissionProfile($permission, $profile){
        $key = 'get-permission-profile-'.$permission.'-'.$profile;
        $data = array();

        if (!Cache::has($key)) {
            $permission_role = PermissionRole::where('permission_id', $permission)->where('role_id', $profile)->get()->toArray();

            $data = !empty($permission_role[0]) ? $permission_role[0] : array();

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function get($id){
        $key = 'get-roles-'.$id;

        if (!Cache::has($key)) {
            $data = Roles::find($id);

            if (count($data->id))
                Cache::put($key, $data, CACHE_DAY);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

}