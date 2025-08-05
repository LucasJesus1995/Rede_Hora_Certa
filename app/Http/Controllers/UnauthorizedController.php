<?php

namespace App\Http\Controllers;


class UnauthorizedController extends Controller{

    public function __construct() {

    }

    public function getIndex(){
        return View("unauthorized.index");
    }

} 