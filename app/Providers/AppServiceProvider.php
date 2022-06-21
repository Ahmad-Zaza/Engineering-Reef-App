<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // URL::forceScheme('https');

        view()->composer('layout.layout', function ($view) {
            $lang = \LaravelLocalization::getCurrentLocale();
           

            $lang_ = ($lang == 'en') ? 1 : 2;

            $menus = \DB::table('cms_menus')
                ->select(
                    "cms_menus.name as title_en",
                    "cms_menus.type as type",
                    "cms_menus.path as url",
                    "cms_menus.id as id",
                    "cms_menus.parent_id"
                )->where('cms_menus.is_active', 1)->where('cms_menus.is_front', 1)->where('parent_id','=',0)->where('lang_id', $lang_)->orderBy("cms_menus.sorting")
                ->get();

            $sons =  \DB::table('cms_menus')
            ->select(
                "cms_menus.name as title_en",
                "cms_menus.type as type",
                "cms_menus.path as url",
                "cms_menus.id as id",
                "cms_menus.parent_id"
            )->where('cms_menus.is_active', 1)->where('cms_menus.is_front', 1)->where('parent_id','!=',0)->where('lang_id', $lang_)->orderBy("cms_menus.sorting")
            ->get();

            foreach ($sons as $item) {
                foreach ($menus as $m) {
                    if ($item->parent_id == $m->id) {
                        $m->sons[] = $item;
                    }
                }
            }

            $info=\DB::table('info_site')
            ->select(
                'address_' . $lang . ' as address',
                'open_hours_' . $lang . ' as open_hours',
                'about_footer_' . $lang . ' as about_footer',
                'email',
                'phone'
            )->where(
                'active',1
            )->first();


            $footer_links=\DB::table('footer_links')
            ->select(
                'name_' . $lang . ' as name',
                'link',
                'id'
            )->where(
                'active',1
            )->get();
            
              $socials = \DB::table('social_media')->orderBy('sorting', 'asc')->where([
                'active' => 1,
            ])->get();
           

            $view->with([
                "menus" => $menus,
                "socials" => $socials,
                "info" =>$info,
                "footer_links"=>$footer_links
            ]);
        });

    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}