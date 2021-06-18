<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Positions;
use App\Http\Requests\CreatePositionsRequest;
use App\Http\Requests\UpdatePositionsRequest;
use Illuminate\Http\Request;


class PositionsController extends Controller {

	/**
	 * Display a listing of .admin.Positions
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $positions = Positions::all();

		return view('admin.Positions.index', compact('positions'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objPositions = new Positions();
			$data = $objPositions->listing($input);

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
	 * Show the form for creating a new positions
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.admin.Positions.create');
	}

	/**
	 * Store a newly created positions in storage.
	 *
     * @param CreatePositionsRequest|Request $request
	 */
	public function store(CreatePositionsRequest $request)
	{
	    
		Positions::create($request->all());

		return redirect()->route(config('quickroute').'admin.positions.index');
	}

	/**
	 * Show the form for editing the specified positions.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$positions = Positions::find($id);
	    
		return view('.admin.Positions.edit', compact('positions'));
	}

	/**
	 * Update the specified positions in storage.
     * @param UpdatePositionsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdatePositionsRequest $request)
	{
		$positions = Positions::findOrFail($id);

		$positions->update($request->all());
		return redirect()->route(config('quickroute').'admin.positions.index')->withMessage('Successfully Updated.');
	}

	/**
	 * Remove the specified positions from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Positions::destroy($id);

		return redirect()->route(config('quickroute').'admin.positions.index');
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
            Positions::destroy($toDelete);
        } else {
            Positions::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'admin.positions.index');
    }

}
