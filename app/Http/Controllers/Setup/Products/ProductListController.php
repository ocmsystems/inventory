<?php

namespace App\Http\Controllers\Setup\Products;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\ProductList;
use App\Http\Requests\CreateProductListRequest;
use App\Http\Requests\UpdateProductListRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\ProductCategories;
use App\Models\ProductClassifications;


class ProductListController extends Controller {

	/**
	 * Display a listing of .products.ProductList
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $productlist = ProductList::with("productcategories")->get();

		return view('Setup.Products.ProductList.index', compact('productlist'));
	}
	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}

			$objProductList = new ProductList();
			$data = $objProductList->listing($input);

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
	 * Show the form for creating a new productlist
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $productcategories = ProductCategories::pluck("name", "id");
	    $productclassifications = ProductClassifications::pluck("name", "id");

	    
	    return view('Setup.Products.ProductList.create', compact("productcategories", "productclassifications"));
	}

	/**
	 * Store a newly created productlist in storage.
	 *
     * @param CreateProductListRequest|Request $request
	 */
	public function store(CreateProductListRequest $request)
	{
		$request = $this->saveFiles($request);
		
		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}

		ProductList::create($input);

		return redirect()->route(config('quickroute').'.productlist.index');
	}

	/**
	 * Show the form for editing the specified productlist.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$productlist = ProductList::find($id);
	    $productcategories = ProductCategories::pluck("name", "id");
	    $productclassifications = ProductClassifications::pluck("name", "id");

	    
		return view('Setup.Products.ProductList.edit', compact('productlist', "productcategories", "productclassifications"));
	}

	/**
	 * Update the specified productlist in storage.
     * @param UpdateProductListRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateProductListRequest $request)
	{
		$productlist = ProductList::findOrFail($id);


        $request = $this->saveFiles($request);

		$input = $request->all();
		if(!isset($input['status'])){
			$input['status'] = 0;
		}
		
		$productlist->update($input);

		return redirect()->route(config('quickroute').'.productlist.index');
	}

	/**
	 * Remove the specified productlist from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ProductList::destroy($id);

		return redirect()->route(config('quickroute').'.productlist.index');
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
            ProductList::destroy($toDelete);
        } else {
            ProductList::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.productlist.index');
    }

}
