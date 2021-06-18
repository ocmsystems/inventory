<?php

namespace App\Http\Controllers\Inventory\Operations;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;

use App\Models\Replenishments;
use App\Models\ReplenishmentDetails;

use App\Http\Requests\CreateReplenishmentsRequest;
use App\Http\Requests\UpdateReplenishmentsRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Validator;



use App\WarehouseList;
use App\Models\Notifications;

class ReplenishmentsController extends Controller {

	/**
	 * Display a listing of Inventory.Operations.Replenishments
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {	

		$replenishments = Replenishments::with("destination_warehouse")
									->with('prepared_by_user')
									->get();


		return view('Inventory.Operations.Replenishments.index', compact('replenishments'));
	}

	public function listing(Request $request){
		
		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}

			$objReplenishments = new Replenishments();
			$data = $objReplenishments->listing($input);

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
	 * Show the form for creating a new replenishments
	 *
     * @return \Illuminate\View\View
	 */
	public function create(){

	    $warehouselist = WarehouseList::pluck("name", "id");
	    
	    return view('Inventory.Operations.Replenishments.create', compact("warehouselist"));
	}

	/**
	 * Store a newly created replenishments in storage.
	 *2
     * @param CreateReplenishmentsRequest|Request $request
	 */
	public function store(CreateReplenishmentsRequest $request){
		
		$input = $request->all();
		$input['scheduled_date'] = Carbon::createFromFormat('M d, Y', $input['scheduled_date'])->format('Y-m-d');
		$input['status'] = Replenishments::statusButton($input['status']);
		
		$replenishment = Replenishments::create($input);

		$replenishment->transaction_number = createTransactionNumber('RM', $replenishment->id);
		$replenishment->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){

			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objReplenishmentDetails = new ReplenishmentDetails();

				$objReplenishmentDetails->replenishment_id = $replenishment->id;
				$objReplenishmentDetails->product_id = $input['product']['name'][$ctr];
				$objReplenishmentDetails->requested_qty = $input['product']['request_qty'][$ctr];

				$objReplenishmentDetails->save();
			}

		}


		return redirect()->route(config('quickroute').'.replenishments.index')->withMessage('Replenishment successfully created.');
	}

	/**
	 * Show the form for editing the specified replenishments.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$replenishments = Replenishments::with('details.product')
										->with('prepared_by_user')
										->findOrFail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		
		if($replenishments->status == 0){
			return view('Inventory.Operations.Replenishments.edit', compact('replenishments', "warehouselist"));
		}else{
			return redirect()->route(config('quickroute').'.replenishments.show', array($id) );
		}
	}

	/**
	 * Update the specified replenishments in storage.
     * @param UpdateReplenishmentsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateReplenishmentsRequest $request)
	{

		$replenishments = Replenishments::findOrFail($id);

		$input = $request->all();
		$input['status'] = Replenishments::statusButton($input['status']);

		if($input['status'] <= 1){
			$input['scheduled_date'] = Carbon::createFromFormat('M d, Y', $input['scheduled_date'])->format('Y-m-d');
			$replenishments->update($input);
	
			$replenishments->details()->delete();
			if( isset($input['product']) && count($input['product']['name']) > 0 ){
	
				for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
					$objReplenishmentDetails = new ReplenishmentDetails();
	
					$objReplenishmentDetails->replenishment_id = $id;
					$objReplenishmentDetails->product_id = $input['product']['name'][$ctr];
					$objReplenishmentDetails->requested_qty = $input['product']['request_qty'][$ctr];
	
					$objReplenishmentDetails->save();
				}
	
			}

			if($input['status'] == 1){
				// Notifications::notify_approvers([
				// 	'message' => 'Replenishment #' . $replenishments->transaction_number . ' has been submitted for approval.',
				// 	'link' => route('.replenishments.show', $replenishments->id),
				// 	'channel' => 'replenishments-approval-channel'
				// ]);
			}
	
			return redirect()->route(config('quickroute').'.replenishments.index')->withMessage('Replenishment #' . $replenishments->transaction_number . ' successfully updated.');
		}else{

			$request->validate([
				'details.approved_qty.*' => 'required|integer|'
			], ['details.approved_qty.*' => 'Approved quantity is required'] );
			
			$replenishments = Replenishments::find($id);
			$replenishments->status = $input['status'];

			$replenishments->save();

			foreach($input['details']['approved_qty'] as $key => $val){
				$objReplenishmentDetails = ReplenishmentDetails::findOrFail($key);

				$objReplenishmentDetails->approved_qty = $val;
				$objReplenishmentDetails->save();
			}

			return redirect()->route(config('quickroute').'.replenishments.index')->withMessage('Replenishment #' . $replenishments->transaction_number . ' successfully approved.');

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
		$replenishments = Replenishments::with('details.product')
										->with('prepared_by_user')
										->findOrfail($id);

		$warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		return view('Inventory.Operations.Replenishments.view', compact('replenishments', "warehouselist"));
	}

	/**
	 * Remove the specified replenishments from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Replenishments::destroy($id);

		return redirect()->route(config('quickroute').'.replenishments.index');
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
            Replenishments::destroy($toDelete);
        } else {
            Replenishments::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.replenishments.index');
    }

}
