<?php

namespace App\ImageUpload;

use App\Form;
use App\Image;
use App\FleetImage;

use phpDocumentor\Reflection\Types\Boolean;

class ImageManager 
{
    public  function store($request,$fleet_id)
    {
        try {
            $image = $request->file('file');
            $imageName = uniqid()."_".time().".".$image->getClientOriginalExtension();
            $image->move('images',$imageName);

            $imageUpload = new FleetImage();
            $imageUpload->image_path = "images/".$imageName;
            $imageUpload->fleet_id = $fleet_id;

            $imageUpload->save();

            return $imageName;
        } catch (\Exception $e) {
            throw $e;
        }

    }
    public  function get($id)
    {
        $form=Form::where(EImage::id,$id)->first();
        return $form;

    }
    public  function getAll($fleet_id)
    {
        $images=\DB::table("fleet_image")->where("fleet_id",$fleet_id)->get();
        return $images;
    }
    
    public  function edit($id)
    {

    }
    public  function  update($request,$id)
    {

    }

    public  function delete($id)
    {
        $file =FleetImage::where('id',$id)->first();

        $path=$file->image_path;
        $file->delete();
        
        if (file_exists($path)) {
            unlink($path);
        }
        return $id;
    }
}
