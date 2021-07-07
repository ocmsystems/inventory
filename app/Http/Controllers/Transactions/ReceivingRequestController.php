<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\ReceivingRequest;
use App\Models\ReceivingRequestDetails;
use App\Http\Requests\CreateReceivingRequestRequest;
use App\Http\Requests\UpdateReceivingRequestRequest;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\WarehouseList;
use App\Models\BusinessUnit;
use App\Models\Drivers;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Helpers;


class ReceivingRequestController extends Controller {

	/**
	 * Display a listing of .transactions.ReceivingRequest
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $receivingrequest = ReceivingRequest::with("warehouselist")->with("businessunit")->get();

		return view('.transactions.ReceivingRequest.index', compact('receivingrequest'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objReceivingRequest = new ReceivingRequest();
			$data = $objReceivingRequest->listing($input);

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
	 * Show the form for creating a new receivingrequest
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $warehouselist = WarehouseList::pluck("name", "id");
		$businessunit = BusinessUnit::pluck("name", "id");

	    
	    return view('.transactions.ReceivingRequest.create', compact("warehouselist", "businessunit"));
	}

	/**
	 * Store a newly created receivingrequest in storage.
	 *
     * @param CreateReceivingRequestRequest|Request $request
	 */
	public function store(CreateReceivingRequestRequest $request)
	{
		$input = $request->all();
	    
		$input['requested_date'] = Carbon::createFromFormat('M d, Y', $input['requested_date'])->format('Y-m-d');
		$input['status'] = ReceivingRequest::statusButton($input['status']);
		$receivingrequest = ReceivingRequest::create($input);

		$receivingrequest->transaction_number = createTransactionNumber('RR', $receivingrequest->id);
		$receivingrequest->save();

		if( isset($input['product']) && count($input['product']['name']) > 0 ){
	
			for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
				$objReceivingRequestDetails = new ReceivingRequestDetails();

				$objReceivingRequestDetails->receivingrequest_id = $receivingrequest->id;
				$objReceivingRequestDetails->product_id = $input['product']['name'][$ctr];
				$objReceivingRequestDetails->requested_qty = $input['product']['request_qty'][$ctr];

				$objReceivingRequestDetails->save();
			}

		}

		return redirect()->route(config('quickroute').'.receivingrequest.index')->withMessage('Receiving request successfully created.');
	}

	/**
	 * Show the form for editing the specified receivingrequest.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$receivingrequest = ReceivingRequest::find($id);
	    $warehouselist = WarehouseList::pluck("name", "id")->prepend('Please select', 0);
		$businessunit = BusinessUnit::pluck("name", "id")->prepend('Please select', 0);

	    
		return view('.transactions.ReceivingRequest.edit', compact('receivingrequest', "warehouselist", "businessunit"));
	}

	/**
	 * Update the specified receivingrequest in storage.
     * @param UpdateReceivingRequestRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateReceivingRequestRequest $request)
	{
		$receivingrequest = ReceivingRequest::findOrFail($id);
		$input = $request->all();
		$input['status'] = ReceivingRequest::statusButton($input['status']);
		
		switch($input['status']){
			case 0:
			case 1:
				$input['requested_date'] = Carbon::createFromFormat('M d, Y', $input['requested_date'])->format('Y-m-d');
				$receivingrequest->update($input);
		
				$receivingrequest->details()->delete();
				if( isset($input['product']) && count($input['product']['name']) > 0 ){
		
					for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
						$objReceivingRequestDetails = new ReceivingRequestDetails();
		
						$objReceivingRequestDetails->receivingrequest_id = $id;
						$objReceivingRequestDetails->product_id = $input['product']['name'][$ctr];
						$objReceivingRequestDetails->requested_qty = $input['product']['request_qty'][$ctr];
		
						$objReceivingRequestDetails->save();
					}
		
				}
				return redirect()->route(config('quickroute').'.receivingrequest.index')->withMessage('Receiving Request #' . $receivingrequest->transaction_number . ' successfully updated.');

				break;
			
			case 2:
				$input['scheduled_date'] = Carbon::createFromFormat('M d, Y', $input['scheduled_date'])->format('Y-m-d');

				$validated = $request->validate([
					'driver_id' => 'required',
					'helper_id' => 'required',
				],
				[
					'driver_id.required' => 'Driver is required.',
					'helper_id.required' => 'Helper is required.',
				]);
					
				$receivingrequest->scheduled_date = $input['scheduled_date'];
				$receivingrequest->driver_id = $input['driver_id'];
				$receivingrequest->helper_id = $input['helper_id'];
				$receivingrequest->status = $input['status'];
	
				$receivingrequest->save();
				return redirect()->route(config('quickroute').'.receivingrequest.index')->withMessage('Receiving Request #' . $receivingrequest->transaction_number . ' successfully updated.');

				break;

			case 3:
			case 4:
				$receivingrequest->status = $input['status'];
				$receivingrequest->save();
				return redirect()->route(config('quickroute').'.receivingrequest.index')->withMessage('Receiving Request #' . $receivingrequest->transaction_number . ' successfully updated.');
			case 5:
					$request = $this->saveFiles($request);
					$validated = $request->validate(['filename' => 'required'], ['filename.required' => 'Copy of Receiving Form is required.']);

					$receivingrequest->status = $input['status'];
					$receivingrequest->receiving_form_file = $request['filename'];
					$receivingrequest->save();

					if(isset($input['details'])){
						foreach($input['details']['qty'] as $product_id => $received_qty){
							$reqDetails = ReceivingRequestDetails::find($input['details']['id'][$product_id]);
							$reqDetails->received_qty = $received_qty;
							$reqDetails->save();
						}
					}

					return redirect()->route(config('quickroute').'.receivingrequest.index')->withMessage('Receiving Request #' . $receivingrequest->transaction_number . ' successfully updated.');

				break;
			case 6:
					$receivingrequest->status = 3;

					if(isset($input['details'])){
						foreach($input['details']['qty'] as $product_id => $received_qty){
							$reqDetails = ReceivingRequestDetails::find($input['details']['id'][$product_id]);
							$reqDetails->received_qty = $received_qty;
							$reqDetails->save();
						}
					}
					return redirect()->route('.receivingrequest.show', $receivingrequest->id)->withMessage('Receiving Request #' . $receivingrequest->transaction_number . ' successfully updated.');

				break;


			default:
			break;
		}

	}

	/**
	 * Remove the specified receivingrequest from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		ReceivingRequest::destroy($id);

		return redirect()->route(config('quickroute').'.receivingrequest.index');
	}


	/**
	 * Show the form for editing the specified replenishments.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		$receivingrequest = ReceivingRequest::with('details.product')
										->with('prepared_by_user')
										->findOrfail($id);
		$businessunit = BusinessUnit::pluck("name", "id");
		$warehouselist = WarehouseList::pluck("name", "id");

		$drivers = Drivers::pluck("name", "id");
		$helpers = Helpers::pluck("name", "id");

		return view('.transactions.ReceivingRequest.view', compact('receivingrequest', "warehouselist", "businessunit", "drivers", "helpers"));
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
            ReceivingRequest::destroy($toDelete);
        } else {
            ReceivingRequest::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.receivingrequest.index');
    }

}
