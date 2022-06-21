<?php

namespace App\Http\Controllers;

use App\InfoPages;
use Illuminate\Http\Request;

class PageInfoController extends Controller
{
    public function get($model)
    {
        if (!\CRUDBooster::myId()) {
            return redirect('modules/login');
        }

        \LaravelLocalization::setLocale("En");

        $conditions = array();
        array_push($conditions, ['pages', 'like', $model]);
        $data = InfoPages::where($conditions)->first();
        return view('InfoPages.index', array('data' => $data, 'type' => $model));
    }

    public function store($model, Request $request)
    {
        if ($request->isMethod('post')) {

            $conditions = array();
            array_push($conditions, ['pages', '=', $model]);
            $InfoPagesOld = InfoPages::where($conditions)->delete();

            $InfoPages = new InfoPages();
            $InfoPages->breif_en = ($request->breif_en) ? $request->breif_en : null;
            $InfoPages->breif_ar = ($request->breif_ar) ? $request->breif_ar : null;
            $InfoPages->pages = $model;

            $InfoPages->save();

            return redirect('admin/information/' . $model);

        }

    }

 
    public function viewpage($page_id)
    {
        $page=\DB::table('landing_pages')->find($page_id);
        if($page==null){
            return null;
        }
        $seo = \DB::table('seo')->where([
            'model' => 'page',
            'model_id' => $page_id,
        ])->first();

        if (!$seo) {
            $seo = \DB::table('seo')->where([
                'model' => 'home',
                'model_id' => null,
            ])->first();
        }

        return view("pageview",array("data"=>$page,"seo"=>$seo)) ;

    }

    private function stripSpace($string)
    {   
        return $string= str_replace(' ','', trim($string));
    }

    private function getStringBetween($str,$from,$to)
    {
        $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
        return substr($sub,0,strpos($sub,$to));
    }
    

    private function getForm($id)
    {
        $form= \DB::table('forms')->find($id);
        $elemnt_form="";
        if($form){
            $elemnt_form.="<form method='POST' action='/request/form/".$form->id."' class='".$form->row_type." well' style='background:#FFF' >";
            $fileds=\DB::table('form_field')->select(
                'form_field.*',
                'fields.title',
                'forms.*'
            )
            ->join('fields','form_field.field_id','=','fields.id')
            ->join('forms','form_field.form_id','=','forms.id')
            ->where('form_field.form_id',$form->id)->get();
            // return  response()->json($fileds, 200);
            
            if($fileds){
                foreach ($fileds as $item) {
                    $req=($item->required_filed=='Yes')?"required":"";
                    $elemnt_form.="<div class='form-group'>";
                    $elemnt_form.="<label>".$item->label_filed.":</label>";
                    if($item->title=='email' || $item->title=='text'){
                        $elemnt_form.="<input type='".$item->title."' class='form-control' name='".$this->stripSpace($item->label_filed)."' ".$req." />";                        
                    }
                    else if($item->title=='checkbox'){
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form.="<label><input type='".$item->title."'  value='".$filed."' name='".$this->stripSpace($item->label_filed)."[]' ".$req." />".$filed."</label><br>";                                                
                        }
                    }
                    else if($item->title=='radio'){
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form.="<label><input type='".$item->title."'  value='".$filed."' name='".$this->stripSpace($item->label_filed)."' ".$req." />".$filed."</label><br>";                                                
                        }
                    }

                    else if($item->title=='select'){
                        $array_values = explode(',', $item->values);
                        $elemnt_form.="<select name='".$this->stripSpace($item->label_filed)."' class='form-control' >";                                                
                        
                        foreach ($array_values as $filed) {
                            $elemnt_form.="<option value='".$filed."'>".$filed."</option>";                                                
                        }
                        $elemnt_form.="</select>";
                    }

                    $elemnt_form.="</div>";
                }
            $elemnt_form.="<div class='form-group'>";
                
            $elemnt_form.="<input type='submit' class='btn btn-primary' value='SEND' />";
            $elemnt_form.="</div>";
            
            $elemnt_form.="</form>";
            
            }
            return  $elemnt_form;
        }
        else{
            return false;
        }
    }
    
}