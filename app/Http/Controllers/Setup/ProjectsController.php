<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Projects;
use App\Http\Requests\CreateProjectsRequest;
use App\Http\Requests\UpdateProjectsRequest;
use Illuminate\Http\Request;



class ProjectsController extends Controller {

	/**
	 * Display a listing of .setup.Projects
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $projects = Projects::all();

		return view('.setup.Projects.index', compact('projects'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objProjects = new Projects();
			$data = $objProjects->listing($input);

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
	 * Show the form for creating a new projects
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('.setup.Projects.create');
	}

	/**
	 * Store a newly created projects in storage.
	 *
     * @param CreateProjectsRequest|Request $request
	 */
	public function store(CreateProjectsRequest $request)
	{
	    
		Projects::create($request->all());

		return redirect()->route(config('quickroute').'.projects.index');
	}

	/**
	 * Show the form for editing the specified projects.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$projects = Projects::find($id);
	    
	    
		return view('.setup.Projects.edit', compact('projects'));
	}

	/**
	 * Update the specified projects in storage.
     * @param UpdateProjectsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateProjectsRequest $request)
	{
		$projects = Projects::findOrFail($id);

        

		$projects->update($request->all());

		return redirect()->route(config('quickroute').'.projects.index');
	}

	/**
	 * Remove the specified projects from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Projects::destroy($id);

		return redirect()->route(config('quickroute').'.projects.index');
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
            Projects::destroy($toDelete);
        } else {
            Projects::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.projects.index');
    }

}
