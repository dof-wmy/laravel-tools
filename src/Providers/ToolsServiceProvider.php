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
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->mergeConfigFrom(
            __DIR__.'/../../config/one-piece.php', 'one-piece'
        );
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'one-piece');
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