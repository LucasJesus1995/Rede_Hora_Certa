<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 23/08/17
 * Time: 01:55
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Dingo\Api\Exception\ValidationHttpException;


class BaseController extends Controller
{
    use Helpers;



}