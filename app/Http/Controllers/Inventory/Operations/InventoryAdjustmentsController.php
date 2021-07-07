<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\InventoryAdjustments;
use App\Models\InventoryAdjustmentDetails;
use App\Http\Requests\CreateInventoryAdjustmentsRequest;
use App\Http\Requests\UpdateInventoryAdjustmentsRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 

use App\WarehouseList;
use App\Models\ProductList;
use App\Models\ProductInventory;

use App\Models\Notifications;

class InventoryAdjustmentsController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.InventoryAdjustments
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $inventoryadjustments = InventoryAdjustments::all();

		return view('Inventory.Operations.InventoryAdjustments.index', compact('inventoryadjustments'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objInventoryAdjustments = new InventoryAdjustments();
			$data = $objInventoryAdjustments->listing($input);

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
	 * Show the form for creating a new inventoryadjustments
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $warehouselist = WarehouseList::pluck("name", "id");
	    return view('Inventory.Operations.InventoryAdjustments.create', compact('warehouselist'));
	}

	/**
	 * Store a newly created inventoryadjustments in storage.
	 *
     * @param CreateInventoryAdjustmentsRequest|Request $request
	 */
	public function store(CreateInventoryAdjustmentsRequest $request)
	{
		$input = $request->all();

		$input['date'] = Carbon::createFromFormat('M d, Y', $input['date'])->format('Y-m-d');
		$input['status'] = InventoryAdjustments::statusButton($input['status']);
		
		$adjustment = InventoryAdjustments::create($input);

		$adjustment->transaction_number = createTransactionNumber('IA', $adjustment->id);
		$adjustment->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){
			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objInventoryAdjustmentDetails = new InventoryAdjustmentDetails();

				$objInventoryAdjustmentDetails->adjustment_id = $adjustment->id;
				$objInventoryAdjustmentDetails->product_id = $input['product']['name'][$ctr];
				$objInventoryAdjustmentDetails->adjusted_quantity = $input['product']['adjustment_qty'][$ctr];

				$objInventoryAdjustmentDetails->save();
			}
		}

		return redirect()->route(config('quickroute').'.inventoryadjustments.index')->withMessage('Inventory Adjustment Successfully created.');
	}

	/**
	 * Show the form for editing the specified inventoryadjustments.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$inventoryadjustments = InventoryAdjustments::with('details.product')->findOrFail($id);
	    $warehouselist = WarehouseList::pluck("name", "id");
	    
		if($inventoryadjustments->status == 0){
			return view('Inventory.Operations.InventoryAdjustments.edit', compact('inventoryadjustments', 'warehouselist'));
		}else{
			return redirect()->route(config('quickroute').'.inventoryadjustments.show', array($id) );
		}
	}

	/**
	 * Update the specified inventoryadjustments in storage.
     * @param UpdateInventoryAdjustmentsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateInventoryAdjustmentsRequest $request)
	{
		$inventoryadjustments = InventoryAdjustments::findOrFail($id);
		$input = $request->all();
		$input['status'] = InventoryAdjustments::statusButton($input['status']);

		if($input['status'] <= 1){
			$input['date'] = Carbon::createFromFormat('M d, Y', $input['date'])->format('Y-m-d');

			$inventoryadjustments->update($input);
			$inventoryadjustments->details()->delete();
			
			if( isset($input['product']) && count($input['product']['name']) > 0 ){
				for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
					$objInventoryAdjustmentDetails = new InventoryAdjustmentDetails();

					$objInventoryAdjustmentDetails->adjustment_id = $inventoryadjustments->id;
					$objInventoryAdjustmentDetails->product_id = $input['product']['name'][$ctr];
					$objInventoryAdjustmentDetails->adjusted_quantity = $input['product']['adjustment_qty'][$ctr];

					$objInventoryAdjustmentDetails->save();
				}
			}

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Inventory Adjustment #' . $inventoryadjustments->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.inventoryadjustments.show', $inventoryadjustments->id),
				// 	'channel' => 'inventoryadjustments-approval-channel'
				// ]);
			}
	

			return redirect()->route(config('quickroute').'.inventoryadjustments.index')->withMessage('Inventory Adjustments #' . $inventoryadjustments->transaction_number . ' successfully updated.');

		}else{

			$inventoryadjustments->status = $input['status'];
			$inventoryadjustments->save();

			if($input['status'] == 2){

				foreach($inventoryadjustments->details as $details){
					$objProductInventory = new ProductInventory();
					$type = 10;
					$adjusted_quantity = $details->adjusted_quantity - ($details->product->on_hand($inventoryadjustments->warehouse_id));
					
					if($adjusted_quantity < 0){
						$type = 20;
					}
	
					$objProductInventory->warehouse_id = $inventoryadjustments->warehouse_id;
					$objProductInventory->product_id = $details->product_id;
					$objProductInventory->quantity = abs($adjusted_quantity);
					$objProductInventory->actual_qty = $adjusted_quantity;
					$objProductInventory->reference = $inventoryadjustments->transaction_number;
					$objProductInventory->reference_table = 'inventoryadjustments';
					$objProductInventory->reference_id = $inventoryadjustments->id;
					$objProductInventory->type = $type;
	
					$objProductInventory->save();
				}
	
			}

			return redirect()->route(config('quickroute').'.inventoryadjustments.index')->withMessage('Inventory Adjustments #' . $inventoryadjustments->transaction_number . ' successfully approved.');

		}

	}

	
	/**
	 * Show the form for editing the specified inventoryadjustments.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$inventoryadjustments = InventoryAdjustments::with('details.product')->findOrFail($id);
	    $warehouselist = WarehouseList::pluck("name", "id");
	    
		return view('Inventory.Operations.InventoryAdjustments.view', compact('inventoryadjustments', 'warehouselist'));
	}

	/**
	 * Remove the specified inventoryadjustments from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		InventoryAdjustments::destroy($id);

		return redirect()->route(config('quickroute').'.inventoryadjustments.index');
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
            InventoryAdjustments::destroy($toDelete);
        } else {
            InventoryAdjustments::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.inventoryadjustments.index');
    }

}
