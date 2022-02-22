<?php

namespace App\Providers;

use App\Facades\Express\Express;
use Illuminate\Support\ServiceProvider;

class ExpressProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register():void
    {
        //注册自定义门面
        //$this->app->singleton('Express',Express::class);
        $this->app->singleton('Express', static function (){
            return new ExPress;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
