<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use App\WarehouseList;

class DiscountsController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(){

		$warehouselist = WarehouseList::pluck("name", "id");

		return view('.Sales.Discounts.index', compact("warehouselist"));
	}

}