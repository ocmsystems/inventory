<?php

namespace App\Http\Controllers\Inventory\Products;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\ProductCategories;
use App\Http\Requests\CreateProductCategoriesRequest;
use App\Http\Requests\UpdateProductCategoriesRequest;
use Illuminate\Http\Request;



class ProductCategoriesController extends Controller {

	/**
	 * Display a listing of .products.ProductCategories
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $productcategories = ProductCategories::all();

		return view('Inventory.Products.ProductCategories.index', compact('productcategories'));
	}

	/**
	 * Show the form for creating a new productcategories
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('Inventory.Products.ProductCategories.create');
	}

	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}

			$objProductCategories = new ProductCategories();
			$data = $objProductCategories->listing($input);

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
	 * Store a newly created productcategories in storage.
	 *
     * @param CreateProductCategoriesRequest|Request $request
	 */
	public function store(CreateProductCategoriesRequest $request)
	{
	    
		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}

		ProductCategories::create($input);

		return redirect()->route(config('quickroute').'.productcategories.index');
	}

	/**
	 * Show the form for editing the specified productcategories.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$productcategories = ProductCategories::find($id);
	    
	    
		return view('Inventory.Products.ProductCategories.edit', compact('productcategories'));
	}

	/**
	 * Update the specified productcategories in storage.
     * @param UpdateProductCategoriesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateProductCategoriesRequest $request)
	{
		$productcategories = ProductCategories::findOrFail($id);
	    
		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}

		$productcategories->update($input);

		return redirect()->route(config('quickroute').'.productcategories.index');
	}

	/**
	 * Remove the specified productcategories from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ProductCategories::destroy($id);

		return redirect()->route(config('quickroute').'.productcategories.index');
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
            ProductCategories::destroy($toDelete);
        } else {
            ProductCategories::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.productcategories.index');
    }

}
