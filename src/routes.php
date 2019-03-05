<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'     => 'wmy/one-piece/tools',
    'namespace'  => 'WMY\\OnePiece\\Tools\\Controllers',
], function($router){
    $router->get('group_split', 'ToolsController@excelGroupSplitShow');
});