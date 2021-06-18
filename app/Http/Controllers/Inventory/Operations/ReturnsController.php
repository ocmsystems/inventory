<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Http\Requests\CreateReturnsRequest;
use App\Http\Requests\UpdateReturnsRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Validator;

use App\ProductList;
use App\WarehouseList;
use App\Models\ReturnDetails;
use App\Models\Returns;
use App\Models\ProductInventory;


use App\Models\Notifications;

class ReturnsController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.Returns
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
		return view('Inventory.Operations.Returns.index');
	}

	/**
	 * Show the form for creating a new returns
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $warehouselist = WarehouseList::pluck("name", "id");
	    
	    return view('Inventory.Operations.Returns.create', compact("warehouselist"));
	}

	public function listing(Request $request){
		
		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}

			$objReturns = new Returns();
			$data = $objReturns->listing($input);

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
	 * Store a newly created returns in storage.
	 *
     * @param CreateReturnsRequest|Request $request
	 */
	public function store(CreateReturnsRequest $request)
	{
	    
		$input = $request->all();
		$input['datetime_prepared'] = Carbon::createFromFormat('M d, Y', $input['datetime_prepared'])->format('Y-m-d');
		$input['status'] = Returns::statusButton($input['status']);

		$return = Returns::create($input);

		$return->transaction_number = createTransactionNumber('RT', $return->id);
		$return->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){

			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objReturnDetails = new ReturnDetails();

				$objReturnDetails->return_id = $return->id;
				$objReturnDetails->product_id = $input['product']['name'][$ctr];
				$objReturnDetails->qty = $input['product']['request_qty'][$ctr];

				$objReturnDetails->save();
			}

		}

		return redirect()->route(config('quickroute').'.returns.index')->withMessage('Return request successfully created.');
	}

	/**
	 * Show the form for editing the specified returns.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$returns = Returns::with('details.product')
										->with('prepared_by_user')
										->findOrFail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		
		if($returns->status == 0){
			return view('Inventory.Operations.Returns.edit', compact('returns', "warehouselist"));
		}else{
			return redirect()->route(config('quickroute').'.returns.show', array($id) );
		}
	}

	/**
	 * Show the form for editing the specified returns.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$returns = Returns::with('details.product')
										->with('prepared_by_user')
										->findOrfail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		return view('Inventory.Operations.Returns.view', compact('returns', "warehouselist"));
	}

	/**
	 * Update the specified returns in storage.
     * @param UpdateReturnsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateReturnsRequest $request)
	{
		$returns = Returns::findOrFail($id);

		$input = $request->all();
		$input['status'] = Returns::statusButton($input['status']);

		if($input['status'] <= 1){
			$input['datetime_prepared'] = Carbon::createFromFormat('M d, Y', $input['datetime_prepared'])->format('Y-m-d');
			$returns->update($input);
	
			$returns->details()->delete();
			if( isset($input['product']) && count($input['product']['name']) > 0 ){
	
				for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
					$objReturnDetails = new ReturnDetails();
	
					$objReturnDetails->return_id = $id;
					$objReturnDetails->product_id = $input['product']['name'][$ctr];
					$objReturnDetails->qty = $input['product']['request_qty'][$ctr];
	
					$objReturnDetails->save();
				}
	
			}

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Return #' . $returns->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.returns.show', $returns->id),
				// 	'channel' => 'returns-approval-channel'
				// ]);
			}
	
			return redirect()->route(config('quickroute').'.returns.index')->withMessage('Return #' . $returns->transaction_number . ' successfully updated.');
		}else{

			$returns->status = $input['status'];
			$returns->save();

			
			if($input['status'] == 2){
				$remaining = 0;
				foreach($returns->details as $product){
					$objProductInventory = new ProductInventory();
					//receivded quantity
					$objProductInventory->product_id = $product->product_id;
					$objProductInventory->actual_qty = $product->qty;
					$objProductInventory->quantity = $product->qty;
					$objProductInventory->reference = $returns->transaction_number;
					$objProductInventory->reference_id = $returns->id;
					$objProductInventory->reference_table = 'returns';
					$objProductInventory->type = 10;
					$objProductInventory->warehouse_id = $returns->destination_warehouse_id;

					$objProductInventory->save();
				}

				return redirect()->route(config('quickroute').'.returns.index')->withMessage('Return #' . $returns->transaction_number . ' successfully approved.');
			}else{
				return redirect()->route(config('quickroute').'.returns.index')->withMessage('Return #' . $returns->transaction_number . ' successfully declined.');
			}


		}
	}

	/**
	 * Remove the specified returns from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Returns::destroy($id);

		return redirect()->route(config('quickroute').'.returns.index');
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
            Returns::destroy($toDelete);
        } else {
            Returns::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.returns.index');
    }

}
