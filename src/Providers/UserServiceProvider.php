<?php

namespace Weitac\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->router->group(['namespace' => 'Weitac\User\Http\Controllers'], function($router) {
            require __DIR__ . '/../Http/routes.php';
        });

        $this->loadViewsFrom(realpath(__DIR__ . '/../../views'), 'user');

        $this->publishes([__DIR__ . '/../../assets' => base_path('resources/assets/user'),], 'public');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
//		include __DIR__ . '/../../controllers/FrontWxUserController.php';
//		include __DIR__ . '/../../controllers/StaffController.php';
//        include __DIR__ . '/../../controllers/UserGradeController.php';
//		//网站配置的控制器
//        include __DIR__ . '/../../controllers/WxWebController.php';
//		include __DIR__ . '/../../models/WxWeb.php';
//		include __DIR__ . '/../../models/FrontUserpd.php';
//		include __DIR__ . '/../../models/Staff.php';
//                include __DIR__ . '/../../models/UserGrade.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
