<?php
namespace WMY\OnePiece\Tools\Providers;
use Illuminate\Support\ServiceProvider;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * 在注册后进行服务的启动。
     *
     * @return void
     */
    public function boot()
    {logger('1');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'wmy.one-piece.tools');
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 
    }
}