<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
class TemplateController extends Controller
{
    
    public function index()
    {
        $templates=Template::where("is_active",true)->get();
        return view('template.index',array("templates"=>$templates));
    }

    public function fromscratch()
    {

        return view('template.fromscratch');
        
    }
}
