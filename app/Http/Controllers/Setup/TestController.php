<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;

class TestController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index()
    {
		return view('.Setup.Test.index');
	}

}