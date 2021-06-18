<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Deliveries;
use App\Models\DeliveryDetails;
use App\Http\Requests\CreateDeliveriesRequest;
use App\Http\Requests\UpdateDeliveriesRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Validator;

use App\WarehouseList;
use App\Models\Notifications;

class DeliveriesController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.Deliveries
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $deliveries = Deliveries::all();

		return view('Inventory.Operations.Deliveries.index', compact('deliveries'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objDeliveries = new Deliveries();
			$data = $objDeliveries->listing($input);

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
	 * Show the form for creating a new deliveries
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    $warehouselist = WarehouseList::pluck("name", "id");
	    return view('Inventory.Operations.Deliveries.create', compact("warehouselist"));
	}

	/**
	 * Store a newly created deliveries in storage.
	 *
     * @param CreateDeliveriesRequest|Request $request
	 */
	public function store(CreateDeliveriesRequest $request)
	{
		
		$input = $request->all();

		$input['delivery_date'] = Carbon::createFromFormat('M d, Y', $input['delivery_date'])->format('Y-m-d');
		$input['status'] = Deliveries::statusButton($input['status']);

		$delivery = Deliveries::create($input);

		$delivery->transaction_number = createTransactionNumber('DR', $delivery->id);
		$delivery->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){

			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objDeliveryDetails = new DeliveryDetails();

				$objDeliveryDetails->delivery_id = $delivery->id;
				$objDeliveryDetails->product_id = $input['product']['name'][$ctr];
				$objDeliveryDetails->requested_qty = $input['product']['request_qty'][$ctr];

				$objDeliveryDetails->save();
			}

		}

		return redirect()->route(config('quickroute').'.deliveries.index')->withMessage('Delivery Report Successfully created.');
	}

	/**
	 * Show the form for editing the specified deliveries.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$deliveries = Deliveries::with('details.product')
								->with('prepared_by_user')
								->findOrFail($id);

		if($deliveries->status == 0){
			$warehouselist = WarehouseList::pluck("name", "id");
			return view('Inventory.Operations.Deliveries.edit', compact('deliveries', "warehouselist"));
		}else{
			return redirect()->route(config('quickroute').'.deliveries.show', array($id) );
		}

	}

	/**
	 * Update the specified deliveries in storage.
     * @param UpdateDeliveriesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateDeliveriesRequest $request)
	{
		$deliveries = Deliveries::findOrFail($id);

		$input = $request->all();
		$input['status'] = Deliveries::statusButton($input['status']);
		
		if($input['status'] <= 1){
			$input['delivery_date'] = Carbon::createFromFormat('M d, Y', $input['delivery_date'])->format('Y-m-d');
			$deliveries->update($input);
	
			$deliveries->details()->delete();
			if( isset($input['product']) && count($input['product']['name']) > 0 ){
	
				for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
					$objDeliveryDetails = new DeliveryDetails();
	
					$objDeliveryDetails->delivery_id = $id;
					$objDeliveryDetails->product_id = $input['product']['name'][$ctr];
					$objDeliveryDetails->requested_qty = $input['product']['request_qty'][$ctr];
	
					$objDeliveryDetails->save();
				}
	
			}
			

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Delivery #' . $deliveries->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.deliveries.show', $deliveries->id),
				// 	'channel' => 'deliveries-approval-channel'
				// ]);
			}
	

			return redirect()->route(config('quickroute').'.deliveries.index')->withMessage('DR #' . $deliveries->transaction_number . ' successfully updated.');

		}else{
			
			if($input['status'] == 2){
				$validateData = $request->validate([
					'details.approved_qty.*' => 'required|integer|'
				], ['details.approved_qty.*' => 'Approved quantity is required'] );
			}
			
			$deliveries->status = $input['status'];

			$deliveries->save();

			foreach($input['details']['approved_qty'] as $key => $val){
				$objDeliveryDetails = DeliveryDetails::findOrFail($key);

				$objDeliveryDetails->approved_qty = $val;
				$objDeliveryDetails->save();
			}

			return redirect()->route(config('quickroute').'.deliveries.index')->withMessage('DR #' . $deliveries->transaction_number . ' successfully updated.');

		}
	}

	/**
	 * Show the form for editing the specified replenishments.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$deliveries = Deliveries::with('details.product')
									->with('prepared_by_user')
									->find($id);

		if($deliveries){
			$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
			return view('Inventory.Operations.Deliveries.view', compact('deliveries', "warehouselist"));
		}else{
            return abort(401);
		}
	}
	/**
	 * Remove the specified deliveries from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Deliveries::destroy($id);

		return redirect()->route(config('quickroute').'.deliveries.index');
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
            Deliveries::destroy($toDelete);
        } else {
            Deliveries::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.deliveries.index');
    }

}
