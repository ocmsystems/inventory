<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\ProductInventory;
use App\Models\Transactions;
use App\Models\Discounts;



class DashboardController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index()
    {
		$registered_user = User::where('status', 3)->get();
		return view('Dashboard.index', compact('registered_user'));
	}

}
