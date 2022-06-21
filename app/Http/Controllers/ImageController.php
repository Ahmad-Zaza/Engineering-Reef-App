<?php

namespace App\Http\Controllers;

use App\ImageModel;
use App\ImageUpload\ImageManager;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public $_imageManager;

    public function __construct()
    {
        $this->_imageManager = new ImageManager();

    }
    public function index($fleet_id = null)
    {
        $arr = $this->_imageManager->getAll($fleet_id);
        $arr = array("images" => $arr, "model_id" => $fleet_id);
        return view("image", $arr);

    }

    public function showImageJson($fleet_id = null)
    {
        $arr = $this->_imageManager->getAll($fleet_id);
        $arr = array("data" => $arr);
        return response()->json($arr);

    }

    public function fileCreate()
    {
        return view('image.imageupload');
    }

    public function fileStore(Request $request, $fleet_id)
    {
        $imageName = $this->_imageManager->store($request, $fleet_id);
        return response()->json(['success' => $imageName]);
    }

    public function fileDestroy($id)
    {
        $data = $this->_imageManager->delete($id);
        return response()->json($data);
    }

    public function saveImagesModule(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $data = $request->get("data");
                if(is_array($data["paths"])){
                    foreach ($data["paths"] as $item) {
                        $imageModel = new ImageModel();
                        $imageModel->model = $data['model'];
                        $imageModel->model_id = $data['model_id'];
                        $imageModel->path = $item;
                        $imageModel->save();
                    }
                }
                else{
                    $imageModel = new ImageModel();
                    $imageModel->model = $data['model'];
                    $imageModel->model_id = $data['model_id'];
                    $imageModel->path = $data["paths"];
                    $imageModel->save();
                }
                

                $images = \DB::table('image_model')->where([
                    'model' => $data['model'],
                    'model_id' => $data['model_id'],
                ])->get();

                return response()->json(array("data" => $images), 200);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    public function deleteImageModule(Request $request,$id)
    {
        $imageModel = ImageModel::find($id);
        if ($imageModel) {
            $imageModel->delete();
        }

        $data = $request->get("dataValue");

        $images = \DB::table('image_model')->where([
            'model' => $data['model'],
            'model_id' => $data['model_id'],
        ])->get();

        return response()->json(array("data" => $images), 200);

    }
}
