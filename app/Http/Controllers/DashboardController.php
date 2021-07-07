<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\ProductInventory;
use App\Models\Transactions;
use App\Models\Discounts;
use App\Models\ReceivingRequest;



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
		$data = [];
		if(auth()->user()->role->title == 'Administrator'){
			$registered_user = User::where('status', 3)->get();
			$data['registered_user'] = $registered_user;

		}else if(auth()->user()->role->title == 'Project Manager'){
			$receiving_requests = ReceivingRequest::where('prepared_by', auth()->user()->id)->orderBy('requested_date', 'DESC')->get();
			$data['receiving_requests'] = $receiving_requests;
		}

		return view('Dashboard.index', $data);
	}

}
