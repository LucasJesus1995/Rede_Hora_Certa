<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /*
        |--------------------------------------------------------------------------
        | ACL Methods
        |--------------------------------------------------------------------------
        */

    /**
     * Checks a Permission
     *
     * @param  String permission Slug of a permission (i.e: manage_user)
     * @return Boolean true if has permission, otherwise false
     */
    public function can($uri, $permission = null)
    {


//        exit("<pre>" . print_r(Route::currentRouteAction(), 1) . "</pre>"); #debug-ederson
//
//
//        exit("<pre>" . print_r($permission, 1) . "</pre>"); #debug-ederson

        return $this->checkPermission($permission, $uri);
    }

    /**
     * Check if the permission matches with any permission user has
     *
     * @param  String permission slug of a permission
     * @return Boolean true if permission exists, otherwise false
     */
    protected function checkPermission($uri, $perm)
    {
        $permissions = $this->getAllPernissionsFormAllRoles();
        $type = self::getTypeAccess();

        return (boolean)  (array_key_exists($type, $permissions)) ? $permissions[$type] : true;
    }

    /**
     * Get all permission slugs from all permissions of all roles
     *
     * @return Array of permission slugs
     */
    protected function getAllPernissionsFormAllRoles()
    {
        return $this->getRulesByPerfilControllerSlug();

//        $permissionsArray = [];
//        $permissions = $this->roles->load('permissions')->fetch('permissions')->toArray();
//
//        return array_map('strtolower', array_unique(array_flatten(array_map(function ($permission) {
//
//            return array_fetch($permission, 'permission_slug','id');
//
//        }, $permissions))));
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-Many Relationship Method for accessing the User->roles
     *
     * @return QueryBuilder Object
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public static function getPerfil()
    {
        return Auth::user()->level;
    }

    public static function getLote()
    {
        return Auth::user()->lote;
    }

    public static function getId()
    {
        return Auth::user()->id;
    }

    public static function getContrato()
    {
        return Lotes::getContratoByLoteId(self::getLote());
    }

    public static function getControllerName()
    {
        $uri = explode("\\", current(explode("@", Route::currentRouteAction())));

        return !empty(end($uri)) ? end($uri) : false;
    }

    public static function getTypeAccess()
    {
        
        switch (self::getActionName()) {
            case 'getView' :
            case 'getIndex' :
            case 'getCombo' :
            case 'view' :
            case 'getProcedimentos' :
            case 'postGridProcedimentos' :
            case 'getBpa' :
            case 'postBpaFile' :
            case 'postGrid' :
                return 'view';
                break;
            case 'getEntry' :
            case 'postView' :
            case 'postIndex' :
                return 'created';
                break;
            case 'index' :
            case 'getIndex' :
            case 'getList' :
            case 'getGrid' :
            case 'getPrint' :
                return 'list';
                break;
            case 'deleteIndex' :
                return 'delete';
                break;

        }
    }

    public static function getActionName()
    {
        //exit("<pre>" . print_r(Route::currentRouteAction(), 1) . "</pre>"); #debug-ederson
        $uri = explode("@", Route::currentRouteAction());

        return !empty(end($uri)) ? end($uri) : false;
    }

    public function getRulesByPerfilControllerSlug()
    {
        $perfil = self::getPerfil();
        $controller = self::getControllerName();

        $res = PermissionRole::where('role_id', $perfil)
            ->select(['permission_role.*'])
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where(DB::raw('CONCAT(Upper(substr(permissions.permission_slug, 1,1)), lower(substr(permissions.permission_slug, 2,length(permissions.permission_slug))), "Controller")'), $controller)
            ->get()
            ->toArray();

        return !empty($res) ? current($res) : array();
    }

    public static function getPermissions(){

    }

    public static function homologacao() {
        $usuarios = array(1,139,526,527,381,415,52,506,439,11);
        return (in_array(User::getId(), $usuarios));
    }



}
