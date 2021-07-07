<?php

namespace App\Http\Controllers\Inventory;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\WarehouseList;
use App\Models\ProductList;
use App\Models\ProductInventory;

class ProductInventoryController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(){

        $warehouselist = WarehouseList::all();
		return view('Inventory.ProductInventory.index', compact('warehouselist'));
	}


	public function view($wid){

		$data['warehouse'] = WarehouseList::findOrfail($wid);
		$data['products'] = ProductInventory::warehouses($data['warehouse']->id);

		return view('Inventory.ProductInventory.details', compact('data'));
	}


	public function product_history($wid, $pid){

		$warehouse = WarehouseList::findOrfail($wid);
		$product = ProductList::findOrfail($pid);

		$data['list'] = ProductInventory::where('warehouse_id', $wid)
										->where('product_id', $pid)
										->orderBy('insert_datetime', 'desc')
										->get();
		
		return view('Inventory.ProductInventory.product_history', compact('data', 'warehouse', 'product'));

	}


}