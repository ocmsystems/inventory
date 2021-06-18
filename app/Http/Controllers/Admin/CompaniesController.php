<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Companies;
use App\Http\Requests\CreateCompaniesRequest;
use App\Http\Requests\UpdateCompaniesRequest;
use Illuminate\Http\Request;


class CompaniesController extends Controller {

	/**
	 * Display a listing of .admin.Companies
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $companies = Companies::all();

		return view('admin.Companies.index', compact('companies'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objCompanies = new Companies();
			$data = $objCompanies->listing($input);

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
	 * Show the form for creating a new companies
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.admin.Companies.create');
	}

	/**
	 * Store a newly created companies in storage.
	 *
     * @param CreateCompaniesRequest|Request $request
	 */
	public function store(CreateCompaniesRequest $request)
	{
	    
		Companies::create($request->all());

		return redirect()->route(config('quickroute').'admin.companies.index');
	}

	/**
	 * Show the form for editing the specified companies.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$companies = Companies::find($id);
	    
		return view('.admin.Companies.edit', compact('companies'));
	}

	/**
	 * Update the specified companies in storage.
     * @param UpdateCompaniesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCompaniesRequest $request)
	{
		$companies = Companies::findOrFail($id);

		$companies->update($request->all());
		return redirect()->route(config('quickroute').'admin.companies.index')->withMessage('Successfully Updated.');
	}

	/**
	 * Remove the specified companies from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Companies::destroy($id);

		return redirect()->route(config('quickroute').'admin.companies.index');
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
            Companies::destroy($toDelete);
        } else {
            Companies::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'admin.companies.index');
    }

}
