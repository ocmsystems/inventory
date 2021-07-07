<?php

namespace App\Http\Controllers\Setup\Products;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\ProductClassifications;
use App\Http\Requests\CreateProductClassificationsRequest;
use App\Http\Requests\UpdateProductClassificationsRequest;
use Illuminate\Http\Request;



class ProductClassificationsController extends Controller {

	/**
	 * Display a listing of .setup.ProductClassifications
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $productclassifications = ProductClassifications::all();

		return view('Setup.Products.ProductClassifications.index', compact('productclassifications'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objProductClassifications = new ProductClassifications();
			$data = $objProductClassifications->listing($input);

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
	 * Show the form for creating a new productclassifications
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('Setup.Products.ProductClassifications.create');
	}

	/**
	 * Store a newly created productclassifications in storage.
	 *
     * @param CreateProductClassificationsRequest|Request $request
	 */
	public function store(CreateProductClassificationsRequest $request)
	{
	    
		ProductClassifications::create($request->all());

		return redirect()->route(config('quickroute').'.productclassifications.index');
	}

	/**
	 * Show the form for editing the specified productclassifications.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$productclassifications = ProductClassifications::find($id);
	    
	    
		return view('Setup.Products.ProductClassifications.edit', compact('productclassifications'));
	}

	/**
	 * Update the specified productclassifications in storage.
     * @param UpdateProductClassificationsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateProductClassificationsRequest $request)
	{
		$productclassifications = ProductClassifications::findOrFail($id);

        

		$productclassifications->update($request->all());

		return redirect()->route(config('quickroute').'.productclassifications.index');
	}

	/**
	 * Remove the specified productclassifications from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ProductClassifications::destroy($id);

		return redirect()->route(config('quickroute').'.productclassifications.index');
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
            ProductClassifications::destroy($toDelete);
        } else {
            ProductClassifications::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.productclassifications.index');
    }

}
