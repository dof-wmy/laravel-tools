<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'     => '',//one-piece/tools
    'namespace'  => 'WMY\\OnePiece\\Tools\\Controllers',
], function($router){
    $router->group([
        'prefix'     => 'excel',
    ], function($router){
        $router->get('group_split', 'ExcelController@excelGroupSplitShow');
        $router->post('group_split', 'ExcelController@excelGroupSplit');
    });
});