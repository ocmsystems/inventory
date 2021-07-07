<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\BusinessUnit;
use App\Http\Requests\CreateBusinessUnitRequest;
use App\Http\Requests\UpdateBusinessUnitRequest;
use Illuminate\Http\Request;



class BusinessUnitController extends Controller {

	/**
	 * Display a listing of .setup.BusinessUnit
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $businessunit = BusinessUnit::all();

		return view('.setup.BusinessUnit.index', compact('businessunit'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objBusinessUnit = new BusinessUnit();
			$data = $objBusinessUnit->listing($input);

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
	 * Show the form for creating a new businessunit
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.setup.BusinessUnit.create');
	}

	/**
	 * Store a newly created businessunit in storage.
	 *
     * @param CreateBusinessUnitRequest|Request $request
	 */
	public function store(CreateBusinessUnitRequest $request)
	{
	    
		BusinessUnit::create($request->all());

		return redirect()->route(config('quickroute').'.businessunit.index');
	}

	/**
	 * Show the form for editing the specified businessunit.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$businessunit = BusinessUnit::find($id);
	    
	    
		return view('.setup.BusinessUnit.edit', compact('businessunit'));
	}

	/**
	 * Update the specified businessunit in storage.
     * @param UpdateBusinessUnitRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateBusinessUnitRequest $request)
	{
		$businessunit = BusinessUnit::findOrFail($id);

        

		$businessunit->update($request->all());

		return redirect()->route(config('quickroute').'.businessunit.index');
	}

	/**
	 * Remove the specified businessunit from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		BusinessUnit::destroy($id);

		return redirect()->route(config('quickroute').'.businessunit.index');
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
            BusinessUnit::destroy($toDelete);
        } else {
            BusinessUnit::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.businessunit.index');
    }

}
