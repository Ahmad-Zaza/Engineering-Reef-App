<?php

namespace App\Http\Controllers;

use \DB;

class FrontController extends Controller
{
    public $lang;
    public function __construct()
    {
        // /\App::setLocale(session()->get('locale'));
    }

    public function index()
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';

        $seo = DB::table('seo')->where([
            'model' => 'home',
            'model_id' => null,
        ])->first();

        $data["highlights"] = DB::table("highlights")->select(
            'name_' . $this->lang . ' as name',
            'label_' . $this->lang . ' as label',
            'description_' . $this->lang . ' as description',
            'link',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->get();

        $data["why_us"] = DB::table("why_us")->select(
            'desc_' . $this->lang . ' as desc',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(5)->get();

        $data["about_us"] = DB::table("about_us")->select(
            'desc_' . $this->lang . ' as desc',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->first();

        $data["products"] = DB::table("products")->select(
            'name_' . $this->lang . ' as name',
            'breif_' . $this->lang . ' as breif',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(3)->get();


        $data["projects"] = DB::table("projects")->select(
            'name_' . $this->lang . ' as name',
            'desc_' . $this->lang . ' as desc',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(6)->get();

        $data["distributors"] = DB::table("distributors")->select(
            'name_' . $this->lang . ' as name',
            'link',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->get();
        

        $data["news"] = DB::table("news")->select(
            'name_' . $this->lang . ' as name',
            'breif_' . $this->lang . ' as breif',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(3)->get();

        $data["count_projects"] = DB::table("count_projects")->select(
            'name_' . $this->lang . ' as name',
            'count',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(3)->get();

        

        $arr = array('data' => $data, 'seo' => $seo);

        return view('front.index', $arr);

        

    }

    public function products($cat_id=null)
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
                $seo = DB::table('seo')->where([
                    'model' => 'products',
                    'model_id' => null,
                ])->first();

        $data["products"]=DB::table("products")->select(
            'name_' . $this->lang . ' as name',
            'breif_' . $this->lang . ' as breif',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->limit(12)->get();
        $arr = array('data' => $data, 'seo' => $seo);
        

        return view('front.products',$arr);
         
    }

    public function projects()
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
                $seo = DB::table('seo')->where([
                    'model' => 'projects',
                    'model_id' => null,
                ])->first();

        $data["projects"]=DB::table("projects")->select(
            'name_' . $this->lang . ' as name',
            'desc_' . $this->lang . ' as desc',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->get();
        $arr = array('data' => $data, 'seo' => $seo);
        

        return view('front.projects',$arr);
         
    }


    public function news()
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
                $seo = DB::table('seo')->where([
                    'model' => 'news',
                    'model_id' => null,
                ])->first();

        $data["news"]=DB::table("news")->select(
            'name_' . $this->lang . ' as name',
            'breif_' . $this->lang . ' as breif',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->get();
        $arr = array('data' => $data, 'seo' => $seo);
        

        return view('front.news',$arr);
         
    }
    



    public function about_us()
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
                $seo = DB::table('seo')->where([
                    'model' => 'about_us',
                    'model_id' => null,
                ])->first();

        $data["about_us"]=DB::table("about_us")->select(
            'desc_' . $this->lang . ' as desc',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->first();
        $arr = array('data' => $data, 'seo' => $seo);
        

        return view('front.about_us',$arr);
         
    }


    public function catalogues()
    {

        $this->lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
                $seo = DB::table('seo')->where([
                    'model' => 'catalogues',
                    'model_id' => null,
                ])->first();

        $data["catalogues"]=DB::table("catalogues")->select(
            'name_' . $this->lang . ' as name',
            'breif_' . $this->lang . ' as breif',
            'image',
            'id'
        )->where([
            'active' => 1,
        ])->orderBy('sorting', 'asc')->get();
        $arr = array('data' => $data, 'seo' => $seo);
        

        return view('front.catalogues',$arr);
         
    }


    public function contact_us()
    {
        $lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
        $seo = DB::table('seo')->where([
            'model' => 'contact_us',
            'model_id' => null,
        ])->first();

        $data["info"]=\DB::table('info_site')
        ->select(
            'address_' . $lang . ' as address',
            'open_hours_' . $lang . ' as open_hours',
            'about_footer_' . $lang . ' as about_footer',
            'email',
            'phone'
        )->where(
            'active',1
        )->first();
        $arr = array('data' => $data, 'seo' => $seo);
        
        return view('front.contact_us',$arr);
        
    }

    public function faq()
    {
        $lang = (\App::getLocale()) ? \App::getLocale() : 'en';
        
        $seo = DB::table('seo')->where([
            'model' => 'faq',
            'model_id' => null,
        ])->first();

        $data["faq"]=\DB::table('faq')
        ->select(
            'question_' . $lang . ' as question',
            'response_' . $lang . ' as response',
            'id'
        )->where(
            'active',1
        )->get();
        $arr = array('data' => $data, 'seo' => $seo);
        
        return view('front.faq',$arr);
    }

    
    

}
