<?php

/**
 * Package routing file specifies all of this package routes.
 */

use Illuminate\Support\Facades\View;
use Laraveldaily\Quickadmin\Models\Menu;

if (config('quickadmin.standaloneRoutes')) {
    return;
}

if (Schema::hasTable('menus')) {
    $menus = Menu::with('children')->where('menu_type', '!=',  0)->orderBy('position')->get();
    // $menus = Menu::multiLevelMenus();
    View::share('menus', $menus);
    if (! empty($menus)) {
        Route::group([
            'middleware' => ['web', 'auth', 'role'], 
            // 'prefix'     => config('quickadmin.route'),
            'as'         => config('quickadmin.route') . '.',
            'namespace'  => 'App\Http\Controllers',
        ], function () use ($menus) {

            foreach ($menus as $menu) {

                $menu->parent = Menu::find($menu->parent_id);
                if(!empty($menu->parent)){
                    $menu->parent->grandparent = Menu::find($menu->parent->parent_id);
                }

                $url = strtolower($menu->name);
                $controller = $menu->name;
                $as = strtolower($menu->name);

                if(!empty($menu->parent)){
                    $url = strtolower($menu->parent->name) . '/' . $url;
                    $controller = $menu->parent->name . '\\' . $controller;
                    $as = strtolower($menu->parent->name) . '.' . $as;
                }

                if(!empty($menu->parent->grandparent)){
                    $url = strtolower($menu->parent->grandparent->name) . '/' . $url;
                    $controller = $menu->parent->grandparent->name . '\\' . $controller;
                    $as = strtolower($menu->parent->grandparent->name) . '.' . $as;
                }

                switch ($menu->menu_type) {
                    case 1:
                        Route::post( $url . '/massDelete', [
                            'as'   => strtolower($menu->name) . '.massDelete',
                            'uses' => $controller . 'Controller@massDelete'  
                        ]);

                        Route::post( $url . '/listing', [
                            'as'   => strtolower($menu->name) . '.listing',
                            'uses' => $controller . 'Controller@listing'  
                        ]);
                        
                        Route::resource($url, $controller . 'Controller');

                        break;
                    case 3:
                        Route::get($url, [
                            'as'   => $as . '.index',
                            'uses' => $controller . 'Controller@index',
                        ]);
                        
                        break;
                }

            }
        });
    }
}

Route::group([
    'namespace'  => 'Laraveldaily\Quickadmin\Controllers',
    'middleware' => ['web', 'auth']
], function () {
    // Dashboard home page route
    Route::get(config('quickadmin.homeRoute'), config('quickadmin.homeAction','QuickadminController@index'));
    Route::group([
        'middleware' => 'role'
    ], function () {
        // Menu routing
        Route::get('admin/menu', [
            'as'   => 'menu',
            'uses' => 'QuickadminMenuController@index'
        ]);
        Route::post('admin/menu', [
            'as'   => 'menu',
            'uses' => 'QuickadminMenuController@rearrange'
        ]);

        Route::get('admin/menu/edit/{id}', [
            'as'   => 'menu.edit',
            'uses' => 'QuickadminMenuController@edit'
        ]);

        Route::post('admin/menu/edit/{id}', [
            'as'   => 'menu.edit',
            'uses' => 'QuickadminMenuController@update'
        ]);

        Route::get('admin/menu/crud', [
            'as'   => 'menu.crud',
            'uses' => 'QuickadminMenuController@createCrud'
        ]);
        Route::post('admin/menu/crud', [
            'as'   => 'menu.crud.insert',
            'uses' => 'QuickadminMenuController@insertCrud'
        ]);

        Route::get('admin/menu/parent', [
            'as'   => 'menu.parent',
            'uses' => 'QuickadminMenuController@createParent'
        ]);
        Route::post('admin/menu/parent', [
            'as'   => 'menu.parent.insert',
            'uses' => 'QuickadminMenuController@insertParent'
        ]);

        Route::get('admin/menu/custom', [
            'as'   => 'menu.custom',
            'uses' => 'QuickadminMenuController@createCustom'
        ]);
        Route::post('admin/menu/custom', [
            'as'   => 'menu.custom.insert',
            'uses' => 'QuickadminMenuController@insertCustom'
        ]);

        Route::get('admin/actions', [
            'as'   => 'actions',
            'uses' => 'UserActionsController@index'
        ]);

        Route::get('admin/actions/ajax', [
            'as'   => 'actions.ajax',
            'uses' => 'UserActionsController@table'
        ]);


    });
});


// Route::group([
//     'namespace'  => 'App\Http\Controllers\Admin',
//     'middleware' => ['web']
// ], function () {
//     // Point to App\Http\Controllers\UsersController as a resource
//     Route::get('admin/users/data_permissions/{id}', ['as' => 'admin.users.data_permissions', 'uses' => 'UsersController@data_permissions'] );
//     Route::group([
//         'middleware' => 'role'
//     ], function () {
//         Route::resource('admin/users', 'UsersController');
//         Route::resource('admin/roles', 'RolesController');
//     });
//     Route::auth();
// });




