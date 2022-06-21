<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SortingModelController extends Controller
{

    //add by fesal for sorting moduels
    public function sorting(Request $request)
    {
        if ($request->isMethod('post')) {

            if ($request->input("data")) {
                
                $data_arr = $request->input("data");
                $table_name = $request->input("table_name");

                for ($i = 1; $i <= count($data_arr); $i++) {
                    DB::table($table_name)->where("id", $data_arr[$i])->update([
                        'sorting' => $i,
                    ]);
                }

                return response()->json(array("message" => "done", "status" => true));

            } else {
                return response()->json(array("message" => "faild", "status" => false));

            }

        }

    }
}
