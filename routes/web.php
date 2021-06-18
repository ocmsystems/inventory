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

Route::get('/home', function () {
    return redirect('/dashboard');
})->middleware('auth');

Auth::routes();

// Route::get('/dashboard',['as' => 'dashboard.index', 'uses' =>  'DashboardController@index']);
// Route::get('/dashboard/v2', 'DashboardController@versiontwo')->name('v2');
// Route::get('/dashboard/v3', 'DashboardController@versionthree')->name('v3');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);

    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);

    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

    Route::resource('approvers', 'Admin\ApproversController');
    Route::delete('approvers/destroy/{id}', ['uses' => 'Admin\ApproversController@destroy', 'as' => 'approvers.destroy']);


    Route::resource('companies', 'Admin\CompaniesController');
    Route::post('admin/companies/listing', ['uses' => 'Admin\CompaniesController@listing', 'as' => 'companies.listing']);

    Route::resource('positions', 'Admin\PositionsController');
    Route::post('admin/positions/listing', ['uses' => 'Admin\PositionsController@listing', 'as' => 'positions.listing']);

});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


Route::group([
    'middleware' => ['auth'],
    'namespace'  => '\App\Http\Controllers',
], function () {

    Route::get('inventory/productinventory/view/{wid}', ['as' => 'inventory.productinventory.view', 'uses' => 'Inventory\ProductInventoryController@view'] );
    Route::get('inventory/productinventory/product_history/{wid}/{pid}', ['as' => 'inventory.productinventory.product_history', 'uses' => 'Inventory\ProductInventoryController@product_history'] );

    Route::delete('inventory/warehouse/warehouselist/personnel_destroy/{id}', ['as' => 'inventory.warehouselist.personnel.destroy', 'uses' => 'Inventory\Warehouse\WarehouseListController@personnel_destroy'] );
    Route::post('inventory/warehouse/warehouselist/personnel_store', ['as' => 'inventory.warehouselist.personnel.add', 'uses' => 'Inventory\Warehouse\WarehouseListController@personnel_store'] );

    Route::post('sales/reports/salesreporting/generate', ['as' => 'reports.salesreporting.generate', 'uses' => 'Sales\Reports\SalesReportingController@generate'] );
    Route::post('sales/reports/salesreporting/generate_periodic', ['as' => 'reports.salesreporting.generate_periodic', 'uses' => 'Sales\Reports\SalesReportingController@generate_periodic'] );

    Route::post('inventory/reports/physicalcount', ['as' => 'reports.physicalcount.generate', 'uses' => 'Inventory\Reports\PhysicalCountController@generate'] );

});


Route::group([
    'middleware' => ['auth'],
    'namespace'  => '\App\Http\Controllers\Api',
], function () {

    Route::get('api/products/get', ['as' => 'api.products.get', 'uses' => 'ProductsController@get'] );
    Route::get('api/products/get_grouped', ['as' => 'api.products.get_grouped', 'uses' => 'ProductsController@get_grouped'] );
    Route::get('api/products/barcode', ['as' => 'api.products.barcode', 'uses' => 'ProductsController@barcode'] );
    Route::get('api/replenishments/search', ['as' => 'api.replenishments.search', 'uses' => 'ReplenishmentsController@search'] );
    Route::get('api/deliveries/search', ['as' => 'api.deliveries.search', 'uses' => 'DeliveriesController@search'] );
    Route::get('api/users/search', ['as' => 'api.users.search', 'uses' => 'UsersController@search'] );
    
    Route::get('api/users/get_grouped', ['as' => 'api.users.get_grouped', 'uses' => 'UsersController@get_grouped'] );

    Route::post('api/discounts/add', ['as' => 'api.discounts.add', 'uses' => 'DiscountsController@add'] );
    Route::post('api/discounts/update/{id}', ['as' => 'api.discounts.update', 'uses' => 'DiscountsController@update'] );
    Route::get('api/discounts/get', ['as' => 'api.discounts.get', 'uses' => 'DiscountsController@get'] );

    Route::post('api/productinventory/get', ['as' => 'api.productinventory.get', 'uses' => 'ProductInventoryController@get'] );


});