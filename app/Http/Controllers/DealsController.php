<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealsController extends Controller
{
    //
    public function index(){
        return view('deals.index');
    }
}
