<?php

namespace App\Http\Controllers\Setup\Warehouse;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\WarehouseList;
use App\Models\WarehousePersonnel;
use App\Http\Requests\CreateWarehouseListRequest;
use App\Http\Requests\UpdateWarehouseListRequest;
use Illuminate\Http\Request;



class WarehouseListController extends Controller {

	/**
	 * Display a listing of .warehouse/store.WarehouseList
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $warehouselist = WarehouseList::all();

		return view('Setup.Warehouse.WarehouseList.index', compact('warehouselist'));
	}

	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}

			$objWarehouseList = new WarehouseList();
			$data = $objWarehouseList->listing($input);

			if(empty($data)){
				$data['data'] = [];
				$data['recordsTotal'] = 0;
				$data['recordsFiltered'] = 0;
			}

			$data['draw'] = $input['draw'];

			echo  json_encode($data);
			exit;
		}else{
            return abort(401);
		}

	}

	/**
	 * Show the form for creating a new warehouselist
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    $type = [1 => 'Warehouse', 2 => 'Store'];
	    return view('Setup.Warehouse.WarehouseList.create', compact('type'));
	}

	/**
	 * Store a newly created warehouselist in storage.
	 *
     * @param CreateWarehouseListRequest|Request $request
	 */
	public function store(CreateWarehouseListRequest $request)
	{
		
		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}
		WarehouseList::create($input);

		return redirect()->route(config('quickroute').'.warehouselist.index');
	}

	/**
	 * Show the form for editing the specified warehouselist.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
	    $type = [1 => 'Warehouse', 2 => 'Store'];
		$warehouselist = WarehouseList::with('personnel.user')->find($id);
		return view('Setup.Warehouse.WarehouseList.edit', compact('warehouselist', 'type'));
	}

	/**
	 * Update the specified warehouselist in storage.
     * @param UpdateWarehouseListRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateWarehouseListRequest $request)
	{
		$warehouselist = WarehouseList::findOrFail($id);

        
		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}

		$warehouselist->update($input);

		return redirect()->route(config('quickroute').'.warehouselist.index');
	}

	/**
	 * Remove the specified warehouselist from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		WarehouseList::destroy($id);

		return redirect()->route(config('quickroute').'.warehouselist.index');
	}

	public function personnel_store(Request $request){

        $input = $request->all();
		$approver = WarehousePersonnel::create($input);
        return response()->json(['success'=>'Added new Personnel.', 'data' => $approver]);

	}

	public function personnel_destroy($id){
		WarehousePersonnel::destroy($id);

		return response()->json(['success'=>'Personnel successfully deleted.']);
	}

}
