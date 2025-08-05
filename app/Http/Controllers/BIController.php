<?php

namespace App\Http\Controllers;

use App\Arenas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BIController extends Controller
{

    public function getIndex(){
        $view = View("display.bi.index");

        return $view;
    }


}
