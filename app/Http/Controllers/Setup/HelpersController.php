<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Helpers;
use App\Http\Requests\CreateHelpersRequest;
use App\Http\Requests\UpdateHelpersRequest;
use Illuminate\Http\Request;



class HelpersController extends Controller {

	/**
	 * Display a listing of .setup.Helpers
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $helpers = Helpers::all();

		return view('.setup.Helpers.index', compact('helpers'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objHelpers = new Helpers();
			$data = $objHelpers->listing($input);

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
	 * Show the form for creating a new helpers
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.setup.Helpers.create');
	}

	/**
	 * Store a newly created helpers in storage.
	 *
     * @param CreateHelpersRequest|Request $request
	 */
	public function store(CreateHelpersRequest $request)
	{
	    
		Helpers::create($request->all());

		return redirect()->route(config('quickroute').'.helpers.index');
	}

	/**
	 * Show the form for editing the specified helpers.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$helpers = Helpers::find($id);
	    
	    
		return view('.setup.Helpers.edit', compact('helpers'));
	}

	/**
	 * Update the specified helpers in storage.
     * @param UpdateHelpersRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateHelpersRequest $request)
	{
		$helpers = Helpers::findOrFail($id);

        

		$helpers->update($request->all());

		return redirect()->route(config('quickroute').'.helpers.index');
	}

	/**
	 * Remove the specified helpers from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Helpers::destroy($id);

		return redirect()->route(config('quickroute').'.helpers.index');
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
            Helpers::destroy($toDelete);
        } else {
            Helpers::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.helpers.index');
    }

}
