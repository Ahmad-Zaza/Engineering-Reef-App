<?php

namespace App\Http\Controllers;

use App\Seo;
use Illuminate\Http\Request;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
class SEOController extends Controller
{

    public function get($model, $model_id = null)
    {
        if(! \CRUDBooster::myId()) return  redirect('admin/login');
        
        \LaravelLocalization::setLocale("En");
        
        $conditions = array();
        
        if ($model_id!=null) {

            array_push($conditions, ['model', '=', $model]);
            array_push($conditions, ['model_id', '=', $model_id]);
            $data = Seo::where($conditions)->first();

        } else {
            
            array_push($conditions, ['model', '=', $model]);
            array_push($conditions, ['model_id', '=', null]);

            $data = Seo::where($conditions)->first();
        }

        return view('seo.index', array('data' => $data, 'type' => $model, 'id' => $model_id));
    }

    public function store($model, Request $request)
    {
        if ($request->isMethod('post')) {

            $conditions = array();

            if ($request->model_id == null) {

                array_push($conditions, ['model', '=', $model]);
                array_push($conditions, ['model_id', '=', null]);
                $seoOld = Seo::where($conditions)->delete();

            } else {

                array_push($conditions, ['model', '=', $model]);
                array_push($conditions, ['model_id', '=', $request->model_id]);

                $seoOld = Seo::where($conditions)->delete();

            }

            $seo = new Seo();
            $seo->description_en = ($request->description_en) ? $request->description_en : null;
            $seo->keywords_en = ($request->keywords_en) ? $request->keywords_en : null;
            $seo->author_en = ($request->author_en) ? $request->author_en : null;
            $seo->title_en = ($request->title_en) ? $request->title_en : null;
            $seo->description_ar = ($request->description_ar) ? $request->description_ar : null;
            $seo->keywords_ar = ($request->keywords_ar) ? $request->keywords_ar : null;
            $seo->author_ar = ($request->author_ar) ? $request->author_ar : null;
            $seo->title_ar = ($request->title_ar) ? $request->title_ar : null;
            $seo->model_id = ($request->model_id) ? $request->model_id : null;
            $seo->model = $model;

            $seo->save();

           // return redirect($request->back_url)->with('message', trans('crudbooster.alert_update_data_success'),'success');
            CRUDBooster::redirect($request->back_url, trans("crudbooster.alert_update_data_success"), 'success');


        }
    }
}