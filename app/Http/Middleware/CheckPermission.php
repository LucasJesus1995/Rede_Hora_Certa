<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 03/10/15
 * Time: 02:34
 */

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if(User::getPerfil() == 1)
            return $next($request);

        $uri = $this->_parseUriACL($request->getRequestUri());
        if("admin.painel")
            return $next($request);

        if (!app('Illuminate\Contracts\Auth\Guard')->guest()) {
            $slug = current(explode(".", str_replace("admin.", "", $uri)));

            $permissao = \App\Http\Helpers\Util::CheckPermissionAction($slug, 'view');
            if ($permissao)
                return $next($request);

            return $request->ajax ? response('Unauthorized.', 401) : redirect('/unauthorized');
        }
//
//        if (!app('Illuminate\Contracts\Auth\Guard')->guest()) {
//            if ($request->user()->can($uri, $permission)) {
//                return $next($request);
//            }
//
//            return $request->ajax ? response('Unauthorized.', 401) : redirect('/unauthorized');
//        }
    }

    private function _parseUriACL($uri)
    {
        $ur = explode("/", $uri);

        $url[] = $ur[1];
        if(!empty($ur[2]))
            $url[] = $ur[2];

        if(!empty($ur[3]))
            $url[] = $ur[3];

        return implode(".",$url);
    }

} 