<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\PullOuts;
use Carbon\Carbon; 
use App\Models\PullOutDetails;
use App\Http\Requests\CreatePullOutsRequest;
use App\Http\Requests\UpdatePullOutsRequest;
use Illuminate\Http\Request;

use App\Models\Notifications;
use App\Models\ProductInventory;
use App\Models\ProductList;
use App\WarehouseList;

class PullOutsController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.PullOuts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $pullouts = PullOuts::all();

		return view('Inventory.Operations.PullOuts.index', compact('pullouts'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objPullOuts = new PullOuts();
			$data = $objPullOuts->listing($input);

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
	 * Show the form for creating a new pullouts
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $warehouselist = WarehouseList::pluck("name", "id");
	    return view('Inventory.Operations.PullOuts.create', compact("warehouselist"));
	}

	/**
	 * Store a newly created pullouts in storage.
	 *
     * @param CreatePullOutsRequest|Request $request
	 */
	public function store(CreatePullOutsRequest $request)
	{
	    
		$input = $request->all();
		$input['datetime_prepared'] = Carbon::createFromFormat('M d, Y', $input['datetime_prepared'])->format('Y-m-d');
		$input['pullout_date'] = Carbon::createFromFormat('M d, Y', $input['pullout_date'])->format('Y-m-d');
		$input['status'] = PullOuts::statusButton($input['status']);

		$pullout = PullOuts::create($input);

		$pullout->transaction_number = createTransactionNumber('PO', $pullout->id);
		$pullout->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){

			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objPullOutDetails = new PullOutDetails();

				$objPullOutDetails->pullout_id = $pullout->id;
				$objPullOutDetails->product_id = $input['product']['name'][$ctr];
				$objPullOutDetails->qty = $input['product']['request_qty'][$ctr];
				$objPullOutDetails->note = $input['product']['note'][$ctr];

				$objPullOutDetails->save();
			}

		}

		return redirect()->route(config('quickroute').'.pullouts.index')->withMessage('Pull Out request successfully created.');
	}

	/**
	 * Show the form for editing the specified pullouts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
	    
		$pullouts = PullOuts::with('details.product')
						->with('prepared_by_user')
						->findOrFail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);

		if($pullouts->status == 0){
			return view('Inventory.Operations.PullOuts.edit', compact('pullouts', "warehouselist"));
		}else{
			return redirect()->route(config('quickroute').'.pullouts.show', array($id) );
		}
	}

	/**
	 * Update the specified pullouts in storage.
     * @param UpdatePullOutsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdatePullOutsRequest $request)
	{
		$pullouts = PullOuts::findOrFail($id);
		
		$input = $request->all();
		$input['status'] = PullOuts::statusButton($input['status']);
		if($input['status'] <= 1){
			$input['datetime_prepared'] = Carbon::createFromFormat('M d, Y', $input['datetime_prepared'])->format('Y-m-d');
			$input['pullout_date'] = Carbon::createFromFormat('M d, Y', $input['pullout_date'])->format('Y-m-d');
			$pullouts->update($input);
	
			$pullouts->details()->delete();
			if( isset($input['product']) && count($input['product']['name']) > 0 ){
	
				for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
					$objPullOutDetails = new PullOutDetails();
	
					$objPullOutDetails->pullout_id = $id;
					$objPullOutDetails->product_id = $input['product']['name'][$ctr];
					$objPullOutDetails->qty = $input['product']['request_qty'][$ctr];
					$objPullOutDetails->note = $input['product']['note'][$ctr];
	
					$objPullOutDetails->save();
				}
	
			}

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Pull Out #' . $pullouts->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.pullouts.show', $pullouts->id),
				// 	'channel' => 'pullouts-approval-channel'
				// ]);
			}
	
			return redirect()->route(config('quickroute').'.pullouts.index')->withMessage('Pull Out #' . $pullouts->transaction_number . ' successfully updated.');
		}else{

			$pullouts->status = $input['status'];
			$pullouts->save();

			
			if($input['status'] == 2){
				$remaining = 0;
				foreach($pullouts->details as $product){
					$objProductInventory = new ProductInventory();
					//receivded quantity
					$objProductInventory->product_id = $product->product_id;
					$objProductInventory->actual_qty = -$product->qty;
					$objProductInventory->quantity = $product->qty;
					$objProductInventory->reference = $pullouts->transaction_number;
					$objProductInventory->reference_id = $pullouts->id;
					$objProductInventory->reference_table = 'pullouts';
					$objProductInventory->type = 20;
					$objProductInventory->warehouse_id = $pullouts->warehouse_id;

					$objProductInventory->save();
				}

				return redirect()->route(config('quickroute').'.pullouts.index')->withMessage('Pull Out #' . $pullouts->transaction_number . ' successfully approved.');
			}else{
				return redirect()->route(config('quickroute').'.pullouts.index')->withMessage('Pull Out #' . $pullouts->transaction_number . ' successfully declined.');
			}


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
		$pullouts = PullOuts::with('details.product')
						->with('prepared_by_user')
						->findOrfail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		return view('Inventory.Operations.PullOuts.view', compact('pullouts', "warehouselist"));
	}


	/**
	 * Remove the specified pullouts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		PullOuts::destroy($id);

		return redirect()->route(config('quickroute').'.pullouts.index');
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
            PullOuts::destroy($toDelete);
        } else {
            PullOuts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.pullouts.index');
    }

}
