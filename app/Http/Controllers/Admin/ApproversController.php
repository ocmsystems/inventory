<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Models\Approvers;
use Illuminate\Support\Facades\Hash;



class ApproversController extends Controller
{

    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.Approvers.index');
    }

	public function store(Request $request){

        $input = $request->all();
		$approver = Approvers::create($input);
        return response()->json(['success'=>'Added new records.', 'data' => $approver]);
    }

    public function edit($module, Request $request){
        $data = [
            'module_name' =>ucwords( str_replace("_", " ", $module) ),
            'module' => $module,
            'list' => Approvers::with('user')->where('module', $module)->get()
        ];
        
        return view('admin.Approvers.edit', compact('data'));
    }

    
	public function destroy($id){
		Approvers::destroy($id);

		return response()->json(['success'=>'Approver successfully deleted.']);
	}

}