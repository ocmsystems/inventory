<?php
namespace Laraveldaily\Quickadmin\Controllers;

use App\Http\Controllers\Controller;

class QuickadminController extends Controller
{
    /**
     * Show QuickAdmin dashboard page
     *
     * @return Response
     */
    public function index(){

        return redirect()->route('.dashboard.index');
        // return view('admin.dashboard');
    }
}