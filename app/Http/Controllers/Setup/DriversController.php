<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Drivers;
use App\Http\Requests\CreateDriversRequest;
use App\Http\Requests\UpdateDriversRequest;
use Illuminate\Http\Request;



class DriversController extends Controller {

	/**
	 * Display a listing of .setup.Drivers
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $drivers = Drivers::all();

		return view('.setup.Drivers.index', compact('drivers'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objDrivers = new Drivers();
			$data = $objDrivers->listing($input);

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
	 * Show the form for creating a new drivers
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.setup.Drivers.create');
	}

	/**
	 * Store a newly created drivers in storage.
	 *
     * @param CreateDriversRequest|Request $request
	 */
	public function store(CreateDriversRequest $request)
	{
	    
		Drivers::create($request->all());

		return redirect()->route(config('quickroute').'.drivers.index');
	}

	/**
	 * Show the form for editing the specified drivers.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$drivers = Drivers::find($id);
	    
	    
		return view('.setup.Drivers.edit', compact('drivers'));
	}

	/**
	 * Update the specified drivers in storage.
     * @param UpdateDriversRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateDriversRequest $request)
	{
		$drivers = Drivers::findOrFail($id);

        

		$drivers->update($request->all());

		return redirect()->route(config('quickroute').'.drivers.index');
	}

	/**
	 * Remove the specified drivers from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Drivers::destroy($id);

		return redirect()->route(config('quickroute').'.drivers.index');
	}

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            Drivers::destroy($toDelete);
        } else {
            Drivers::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.drivers.index');
    }

}
