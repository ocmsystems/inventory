<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Models\Transactions;
use App\Http\Requests\CreateTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use Illuminate\Http\Request;
use Carbon\Carbon; 

use App\Models\DiscountDetails;
use App\WarehouseList;
use App\Models\ProductList;
use App\Models\ProductInventory;


use App\Models\Notifications;


class TransactionsController extends Controller {

	/**
	 * Display a listing of .sales.Transactions
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $transactions = Transactions::all();

		return view('.Sales.Transactions.index', compact('transactions'));
	}


	
	public function listing(Request $request){

		if( $request->ajax() ) {

			$data = [];
			$input = $request->all();

			if (isset($input['length']) && !empty($input['length'])){
				$input['limit'] = $input['length'];
			}
			
			$objTransactions = new Transactions();
			$data = $objTransactions->listing($input);

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
	 * Show the form for creating a new transactions
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
		if(auth()->user()->role->title == 'Administrator'){
			$warehouselist = WarehouseList::pluck("name", "id");
		}else{
			$warehouselist = auth()->user()->warehouselist();
		}

	    return view('.Sales.Transactions.create', compact("warehouselist"));
	}

	/**
	 * Store a newly created transactions in storage.
	 *
     * @param CreateTransactionsRequest|Request $request
	 */
	public function store(CreateTransactionsRequest $request)
	{
		$input = $request->all();
		$input['transaction_date'] = Carbon::createFromFormat('M d, Y', $input['transaction_date'])->format('Y-m-d');
		$input['status'] = 1;
		$input['quantity'] = 1;
		$input['prepared_by'] = auth()->user()->id;

		$product = ProductList::find($input['product_id']);

		$input['original_price'] = $product->price;
		$input['discount'] = 0;
		$input['amount'] = $product->price;

		$discount = DiscountDetails::join('discounts', 'discounts.id', '=', 'discount_details.discount_id')
						->where('discount_details.product_id', $product->id)
						->where('discounts.status', 1)
						->where('discounts.warehouse_id', $input['warehouse_id'])
						->where('start_datetime', '<=', date("Y-m-d H:i:s"))
						->where('end_datetime', '>=', date("Y-m-d H:i:s"))
						->orderby('discounts.insert_datetime', 'desc')
						->get()->first();
	
		if( $discount && count($discount) > 0){
			$input['original_price'] = $discount->original_price;
			$input['discount'] = $discount->discount;
			$input['amount'] = $discount->discounted_price;
		}
			
		
		$transaction = Transactions::create($input);

		$transaction->transaction_number = createTransactionNumber('SI', $transaction->id);
		$transaction->save();


		
		if($input['status'] == 1){
			// Notifications::notify_approvers([
			// 	'message' => 'Transaction #' . $transaction->transaction_number . ' has been submitted for verification.',
			// 	'link' => route('.transactions.show', $transaction->id),
			// 	'channel' => 'sales_transaction-approval-channel'
			// ]);
		}




		return redirect()->route(config('quickroute').'.transactions.index');
	}

	/**
	 * Show the form for editing the specified transactions.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$transactions = Transactions::find($id);
	    
	    
		return view('.Sales.Transactions.edit', compact('transactions'));
	}

	/**
	 * Update the specified transactions in storage.
     * @param UpdateTransactionsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateTransactionsRequest $request)
	{
		$transactions = Transactions::findOrFail($id);
		$input = $request->all();
		
		$update['status'] = Transactions::statusButton($input['status']);
		$transactions->update($update);

		if($update['status'] == 2){
			$objProductInventory = new ProductInventory();

			$objProductInventory->product_id = $transactions->product_id;
			$objProductInventory->actual_qty = -$transactions->quantity;
			$objProductInventory->quantity = $transactions->quantity;
			$objProductInventory->reference = $transactions->transaction_number;
			$objProductInventory->reference_id = $transactions->id;
			$objProductInventory->reference_table = 'transactions';
			$objProductInventory->type = 20;
			$objProductInventory->warehouse_id = $transactions->warehouse_id;

			$objProductInventory->save();

		}

		return redirect()->route(config('quickroute').'.transactions.index')->withMessage('SI #' . $transactions->transaction_number . ' successfully updated.');;
	}

	public function show($id){
		$transactions = Transactions::with('warehouse')
									->with('product')
									->with('prepared_by_user')
									->find($id);

		$warehouselist = WarehouseList::pluck("name", "id");
		

		return view('.Sales.Transactions.view ', compact('transactions', 'warehouselist'));
	}
	/**
	 * Remove the specified transactions from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Transactions::destroy($id);

		return redirect()->route(config('quickroute').'.transactions.index');
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
            Transactions::destroy($toDelete);
        } else {
            Transactions::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickroute').'.transactions.index');
    }

}
