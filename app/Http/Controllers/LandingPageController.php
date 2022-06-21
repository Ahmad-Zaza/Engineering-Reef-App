<?php

namespace App\Http\Controllers;

use App\LandingPage;
use App\landingPageForm;
use App\Manager\Form\EForm;
use App\Manager\Form\FormManager;
use App\SubmitsForm;
use App\Template;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{

    public $_formManager;

    public function __construct()
    {
        // $this->middleware('auth', ['except' => 'show']);
     //   $this->_formManager = new FormManager();

    }

    public function index()
    {
      
        return view('landing.index');
    }

    public function store($template_id)
    {
        $template = Template::find($template_id);
        return view('landing.create', array("template" => $template));
    }

    public function getAllForms()
    {
       
        $forms = \DB::table('forms')->get();
        foreach ($forms as $item) {
            $item->shortact=$this->getForm($item->id);
        }
        $arr = array("data" => $forms);

        return response()->json($arr);
    }

    public function saveLanding(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'title' => 'required',
                    'code' => 'required',
                ]);

                // $user = \Auth::user();
                $landing = new LandingPage();

                $landing->title = $request->title;
                $landing->code = $request->code;
                $landing->is_active = 1;
                // $landing->user_id = $user->id;
                $landing->background = $request->background;
                $landing->background_image = $request->background_image;
                $landing->background_color = $request->background_color;
                if (isset($request->from_scratch)) {
                    $landing->from_scratch = $request->from_scratch;
                } else {
                    $landing->from_scratch = 0;
                }

                $landing->save();

                // //add this for relation between form and landing page.
                // $landingPage_Form = new landingPageForm();
                // $landingPage_Form->landing_page_id = $landing->id;
                // $landingPage_Form->form_id = $request->form_id;
                // $landingPage_Form->save();

                return response()->json($landing);
            }
        } catch (\Exception $e) {
            return response()->json($e, 500);

        }
    }

  
    public function show($id)
    {
        $landing = LandingPage::find($id);
        return view("landing.show", array("data" => $landing));
    }

    public function edit($id)
    {
        $landing = LandingPage::find($id);
        return view("landing.edit", array("data" => $landing));
    }

    public function get_info($id)
    {
        $landing = LandingPage::find($id);
        return response()->json($landing);
    }


    public function update(Request $request, $id)
    {
        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'title' => 'required',
                    'code' => 'required',
                ]);

                // $user = \Auth::user();
                $landing = LandingPage::find($id);
                $landing->title = $request->title;
                $landing->code = $request->code;
                $landing->background = $request->background;
                $landing->background_image = $request->background_image;
                $landing->background_color = $request->background_color;    
                $landing->save();

                //add this for relation between form and landing page.
                // if ($request->form_id) {
                //     $isFound = landingPageForm::where([
                //         'landing_page_id' => $landing->id,
                //     ])->first();

                //     if ($isFound != null) {
                //         $isFound->landing_page_id = $landing->id;
                //         $isFound->form_id = $request->form_id;
                //         $isFound->save();
                //     } else {
                //         $landingPage_Form = new landingPageForm();
                //         $landingPage_Form->landing_page_id = $landing->id;
                //         $landingPage_Form->form_id = $request->form_id;
                //         $landingPage_Form->save();
                //     }
                // }

                return response()->json(true);
            }
        } catch (\Exception $e) {
            return response()->json($e, 500);

        }
    }


    public function getFormCode($id)
    {
        $data=$this->getForm($id);
        return response()->json($data,200);
    }
    private function getForm($id)
    {
        $form= \DB::table('forms')->find($id);
        $elemnt_form="";
        if($form){
            $elemnt_form.="<div class='col-xs-12'><form method='POST' data-form=".$form->id." action='/request/form/".$form->id."' class='".$form->row_type." well' style='background:#FFF' >";
            $fileds=\DB::table('form_field')->select(
                'form_field.*',
                'fields.title',
                'forms.*'
            )
            ->join('fields','form_field.field_id','=','fields.id')
            ->join('forms','form_field.form_id','=','forms.id')
            ->where('form_field.form_id',$form->id)->get();
            
            if($fileds){
                foreach ($fileds as $item) {
                    $req=($item->required_filed=='Yes')?"required":"";
                    $elemnt_form.="<div class='form-group'>";
                    $elemnt_form.="<label style='display:block'>".$item->label_filed.":</label>";
                    if($item->title=='email' || $item->title=='text'){
                        $elemnt_form.="<input type='".$item->title."' class='form-control' name='".$this->stripSpace($item->label_filed)."' ".$req." />";                        
                    }
                    else if($item->title=='checkbox'){
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form.="<label style='display:block'><input type='".$item->title."'  value='".$filed."' name='".$this->stripSpace($item->label_filed)."[]' ".$req." />".$filed."</label>";                                                
                        }
                    }
                    else if($item->title=='radio'){
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form.="<label style='display:block'><input type='".$item->title."'  value='".$filed."' name='".$this->stripSpace($item->label_filed)."' ".$req." />".$filed."</label>";                                                
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
            
            $elemnt_form.="</form></div>";
            
            }
            return  $elemnt_form;
        }
        else{
            return false;
        }
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
    
}