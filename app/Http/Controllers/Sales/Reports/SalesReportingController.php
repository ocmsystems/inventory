<?php

namespace App\Http\Controllers\Sales\Reports;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\WarehouseList;
use App\User;
use App\Models\Transactions;
use App;
use Dompdf\Dompdf;


class SalesReportingController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index()
    {	

		$userlist = [];
		if(auth()->user()->role->title == 'Administrator'){
			$warehouselist = WarehouseList::pluck("name", "id");
			$userlist = User::pluck("name", "id");
		}else{
			$warehouselist = auth()->user()->warehouselist();
			$userlist[auth()->user()->id] = auth()->user()->name;
		}

		


		return view('Sales.Reports.SalesReporting.index', compact("warehouselist", "userlist"));
	}



	public function generate(Request $request){

		$input = $request->all();

		$query = Transactions::with('product')
							->where('transaction_date', date("Y-m-d", strtotime($input['date'])))
							->where('status', 2)
							->where('warehouse_id', $input['warehouse_id']);
			
		$user_id = auth()->user()->id;

		if(auth()->user()->role->title == 'Administrator'){
			if(isset($input['user_id']) && !empty($input['user_id'])){
				$user_id = $input['user_id'];
			}
		}
		$query->where('prepared_by', $user_id);

		$result = $query->get();


		$total_amount = $result->sum('amount');
		
		$warehouse = WarehouseList::find($input['warehouse_id']);
		$user = User::find($user_id);

		$pdf = App::make('dompdf.wrapper');
		$data = [
			'input' => $input,
			'result' => $result,
			'total_amount' => $total_amount,
			'warehouse' => $warehouse,
			'user' => $user
		];

		$pdf->setPaper('A6', 'landscape');
		$pdf->getDomPDF()->set_option("enable_php", true);
		

		$pdf->loadView('Sales.Reports.SalesReporting.sales', $data);

		return $pdf->stream('DailySalesReport');

	}


	public function generate_periodic(Request $request){

		$input = $request->all();
		 
		$data = [];
		$dates = explode(" - ", $input['daterange']);

		$period = new \DatePeriod(
			new \DateTime($dates[0]),
			new \DateInterval('P1D'),
			new \DateTime($dates[1])
		);

		foreach ($period as $key => $value) {
			$data['dates'][$value->format('Y-m-d')] = [
				'amount' => 0,
				'quantity' => 0,
			];
		}

		if(!empty($input['warehouse_id'])){
			$warehouse = WarehouseList::find($input['warehouse_id']);
		}

		$query = Transactions::selectRaw('sum(amount) as amount, sum(quantity) as quantity, transaction_date')
						->whereBetween('transaction_date', $dates)
						->where('status', 2)
						->orderBy('transaction_date')
						->groupBy('transaction_date','warehouse_id');

		if(!empty($input['warehouse_id'])){
			$query->where('warehouse_id', $input['warehouse_id']);
		}

		$result = $query->get();

		foreach($result as $item){
			$data['dates'][$item->transaction_date] = [
				'amount' => $item->amount,
				'quantity' => $item->quantity,
			];
		}

		$data['warehouse'] = $warehouse;
		$data['range'] = $dates;
		$data['input'] = $input;


		$pdf = App::make('dompdf.wrapper');

		$pdf->setPaper('A4');
		$pdf->getDomPDF()->set_option("enable_php", true);
		

		$pdf->loadView('Sales.Reports.SalesReporting.periodic', $data, array("Attachment" => false));

		return $pdf->stream('DailySalesReport');
	}

}