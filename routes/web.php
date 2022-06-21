<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Route;

$base_url = config('crudbooster.ADMIN_PATH');
\LaravelLocalization::setLocale(config('setting.LANG'));

Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    Artisan::call('config:clear');
    // Artisan::call('config:cache');
    Artisan::call('view:clear');
    // Artisan::call('view:cache');
});

Route::group([

    'prefix' => \LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],

], function () {
    Route::redirect('/', 'modules')->name("home");
});


// Route::get("/import-data",function(){

//     $faq=DB::table("products_catalog_users")
//     ->select(
//         'products_catalog_users.*',
//         'countries.country as country_name'
//     )
//     ->join('countries','countries.id','products_catalog_users.country_id')
//     ->get();

//     foreach ($faq as $key => $value) {
//         DB::table("request_catalogs")->insert([
//             'name'=>$value->full_name,
//             'email'=>$value->email,
//             'company'=>$value->company_name,
//             'tel'=>$value->tel,
//             'code'=>$value->code,
//             'admin_note'=>$value->note,
//             'active'=>$value->active,
//             'country'=>$value->country_name,
//             'date'=>date('Y-m-d'),
//             'ip'=>$value->ip."-".$value->country_name
//         ]);
//     }


// });

Route::post('/contact/request', "FrontController@contactUsRequest");

Route::post('/testimonials/request', "FrontController@testimonialsRequest");
Route::post('/bill/check', "AdminBillsPurchaseInvoiceController@checkBox");


Route::post('request/form/{id}', 'CmsFormController@submit');

Route::get('lang/{locale}', function ($locale) {
    \App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

// Route::get('insertData', function($locale){
//     for ($i=0; $i <100 ; $i++) {
//        $product=new App\Product();
//        $product->name="product".$i;
//        $product->price=$i*10;
//        //fill all fields

//        $product->save();
//     }
// });





Route::get('modules/category-tree-view','CategoryController@manageCategory')->name('category-tree-view');
Route::post('add-category','CategoryController@addCategory')->name('add.category');
Route::get('modules/getToSort/{parent_id}','AdminCategoriesController@getToSort')->name('getToSort');
Route::post('post-sortable','AdminCategoriesController@ordring');




Route::get('/comment/approvecomment/{id}', 'commentController@approvecomment')->name('approvecomment');
Route::get('/comment/rejectioncomment/{id}', 'CommentController@rejectioncomment')->name('rejectioncomment');

//requests
Route::post('/request/save/{id}', "FrontController@requestEvent");
Route::post('/contact_us', "FrontController@contactUsRequest");

Route::get('pages/{id}', "PageInfoController@viewpage");

Route::post($base_url . '/sort', "SortingModelController@sorting");
Route::get($base_url . '/image/{fleet_id?}', "ImageController@index");
Route::get('image/upload', 'ImageController@fileCreate')->name('images.upload');
Route::post('image/upload/store/{fleet_id}', 'ImageController@fileStore');
Route::get('/image/delete/{id}', 'ImageController@fileDestroy');
Route::get('/image/showImageJson/{fleet_id?}', 'ImageController@showImageJson');
Route::get($base_url . '/seo/{model}/{model_id?}', 'SEOController@get');
Route::post('/seo-store/{model}', 'SEOController@store');
Route::get($base_url . '/information/{model}', 'PageInfoController@get');
Route::post('/info-page-store/{model}', 'PageInfoController@store');
Route::post($base_url . '/saveImagesModule', 'ImageController@saveImagesModule');
Route::get($base_url . '/deleteImageModule/{id}', 'ImageController@deleteImageModule');
Route::get($base_url . '/style/form/{id}', 'CmsFormController@getForm');
Route::get($base_url . '/response/form/{id}', 'CmsFormController@getSubmits');
Route::get($base_url . '/viewpage/{page_id}', 'AdminPagesController@viewpage');
Route::get($base_url . '/getForms', 'CmsFormController@getForms');
Route::post('request/form/{id}', 'CmsFormController@submit');
Route::get($base_url . '/languages', 'LanguageTranslationController@index')->name('languages');
Route::post('translations/update', 'LanguageTranslationController@transUpdate')->name('translation.update.json');
Route::post('translations/updateKey', 'LanguageTranslationController@transUpdateKey')->name('translation.update.json.key');
Route::delete('translations/destroy/{key}', 'LanguageTranslationController@destroy')->name('translations.destroy');
Route::post('translations/create', 'LanguageTranslationController@store')->name('translations.create');

//images manage

Route::get('/manage-image/resize/{width?}/{height?}/{img}', function ($width = 100, $height = 100, $img) {
    return \Image::make(public_path("$img"))->resize($width, $height)->response('jpg');
})->name('manage-image-resize')->where('img', '(.*)');

Route::get('/manage-image/crop/{width?}/{height?}/{img}', function ($width = 100, $height = 100, $img) {
    return \Image::make(public_path("$img"))->crop($width, $height)->response('jpg');
})->name('manage-image-crop')->where('img', '(.*)');

Route::get('/landingPage/create/{template_id}', 'LandingPageController@store');
Route::get('/landingPage/getAllForms', 'LandingPageController@getAllForms');
Route::post('/landingPage/save', 'LandingPageController@saveLanding');
Route::get('/landingPages', 'LandingPageController@getAllLandings')->name('landing.index');
Route::get('/landingPage/show/{id}', 'LandingPageController@show');
Route::get('/landingPage/edit/{id}', 'LandingPageController@edit');
Route::post('/landingPage/save/{id}', 'LandingPageController@update');
Route::get('/landingPage/get/{id}', 'LandingPageController@get_info');



##############Export to Execel file ##############

Route::get('deals','DealsController@index');