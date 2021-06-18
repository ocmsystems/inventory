<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Receiving;
use App\Models\ReceivingDetails;

use App\Http\Requests\CreateReceivingRequest;
use App\Http\Requests\UpdateReceivingRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 


use App\WarehouseList;
use App\Models\ProductInventory;
use App\Models\Deliveries;

use App\Models\Notifications;

class ReceivingController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.Receiving
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $receiving = Receiving::all();

		return view('Inventory.Operations.Receiving.index', compact('receiving'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objReceiving = new Receiving();
			$data = $objReceiving->listing($input);

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
	 * Show the form for creating a new receiving
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    $warehouselist = WarehouseList::pluck("name", "id");
	    
	    return view('Inventory.Operations.Receiving.create', compact("warehouselist"));
	}

	/**
	 * Store a newly created receiving in storage.
	 *
     * @param CreateReceivingRequest|Request $request
	 */
	public function store(CreateReceivingRequest $request)
	{	
		$input = $request->all(); 

		$input['received_date'] = Carbon::createFromFormat('M d, Y', $input['received_date'])->format('Y-m-d');
		$input['status'] = Receiving::statusButton($input['status']);

		$receiving = Receiving::create($input);
		
		$receiving->transaction_number = createTransactionNumber('RC', $receiving ->id);
		$receiving->save();
		
		if( isset($input['details']) && count($input['details']['qty']) > 0 ){

			foreach($input['details']['qty'] as $product_id => $products){

				foreach($products as $drd_id => $val){
					$objReceivingDetails = new ReceivingDetails();

					$objReceivingDetails->receiving_id = $receiving->id;
					$objReceivingDetails->product_id = $product_id;
					$objReceivingDetails->delivery_details_id = $drd_id;
					$objReceivingDetails->received_qty = $val;

					$objReceivingDetails->save();
				}

			}

		}

		return redirect()->route(config('quickroute').'.receiving.index')->withMessage('Receiving Report Successfully created.');
	}

	/**
	 * Show the form for editing the specified receiving.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$receiving = Receiving::with('details.product')
							->with('details.dr_details')
							->with('prepared_by_user')
							->with('delivery.source_warehouse')
							->findOrFail($id);

		if($receiving->status == 0){
			$warehouselist = WarehouseList::pluck("name", "id");
			return view('Inventory.Operations.Receiving.edit', compact('receiving', 'warehouselist'));
		}else{
			return redirect()->route(config('quickroute').'.receiving.show', array($id) );
		}
	}

	/**
	 * Update the specified receiving in storage.
     * @param UpdateReceivingRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateReceivingRequest $request)
	{
		$input = $request->all();
		
		$input['received_date'] = Carbon::createFromFormat('M d, Y', $input['received_date'])->format('Y-m-d');
		$input['status'] = Receiving::statusButton($input['status']);

		$receiving = Receiving::findOrFail($id);

		if($input['status'] <= 1){

			$receiving->update($input);

			// $receiving->details()->delete();
			if( isset($input['details']) && count($input['details']['qty']) > 0 ){

				foreach($input['details']['qty'] as $product_id => $products){
					foreach($products as $drd_id => $val){
						
						$objReceivingDetails = ReceivingDetails::findOrFail( $input['details']['id'][$product_id][$drd_id] );

						$objReceivingDetails->received_qty = $val;
						$objReceivingDetails->save();
					}
				}
			}
			

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Receiving #' . $receiving->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.receiving.show', $receiving->id),
				// 	'channel' => 'receiving-approval-channel'
				// ]);
			}
	

			return redirect()->route(config('quickroute').'.receiving.index')->withMessage('RC #' . $receiving->transaction_number . ' successfully updated.');


		}else{
			$receiving->status = $input['status'];
			$receiving->update();
			
			if($input['status'] == 2){
				$remaining = 0;

				foreach($receiving->details as $product){
					$objProductInventory = new ProductInventory();

					$remaining += $product->dr_details->approved_qty - $product->received_qty;

					//receivded quantity
					$objProductInventory->product_id = $product->product_id;
					$objProductInventory->actual_qty = $product->received_qty;
					$objProductInventory->quantity = $product->received_qty;
					$objProductInventory->reference = $receiving->transaction_number;
					$objProductInventory->reference_id = $receiving->id;
					$objProductInventory->reference_table = 'receiving';
					$objProductInventory->type = 10;
					$objProductInventory->warehouse_id = $receiving->receiving_warehouse_id;

					$objProductInventory->save();

					$objProductInventory = new ProductInventory();
					// //delivered quantity
					$objProductInventory->product_id = $product->product_id;
					$objProductInventory->actual_qty = -$product->received_qty;
					$objProductInventory->quantity = $product->received_qty;
					$objProductInventory->reference = $receiving->delivery->transaction_number;
					$objProductInventory->reference_id = $receiving->delivery_id;
					$objProductInventory->reference_table = 'deliveries';
					$objProductInventory->type = 20;
					$objProductInventory->warehouse_id = $receiving->delivery->source_warehouse_id;

					$objProductInventory->save();

				}

				if($remaining <= 0){
					$objDeliveries = Deliveries::find($receiving->delivery_id);
					$objDeliveries->status = 3;
					$objDeliveries->save();
				}

			}

			return redirect()->route(config('quickroute').'.receiving.index')->withMessage('RC #' . $receiving->transaction_number . ' successfully updated.');
		}

	}


	public function show($id){
		
		$receiving = Receiving::with('details.product')
							->with('details.dr_details')
							->with('prepared_by_user')
							->with('delivery.source_warehouse')
							->findOrfail($id);

		if($receiving){
			$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
			return view('Inventory.Operations.Receiving.view', compact('receiving', "warehouselist"));
		}else{
            return abort(401);
		}


	}

	/**
	 * Remove the specified receiving from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Receiving::destroy($id);

		return redirect()->route(config('quickroute').'.receiving.index');
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
            Receiving::destroy($toDelete);
        } else {
            Receiving::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.receiving.index');
    }

}
