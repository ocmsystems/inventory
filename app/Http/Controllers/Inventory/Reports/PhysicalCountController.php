<?php

namespace App\Http\Controllers\Inventory\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WarehouseList;
use App\Models\ProductInventory;
use Dompdf\Dompdf;
use App;

class PhysicalCountController extends Controller {

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
		}else{
			$warehouselist = auth()->user()->warehouselist();
		}
		return view('Inventory.Reports.PhysicalCount.index', compact('warehouselist'));
	}

	public function generate(Request $request){
		$input = $request->all();

		$warehouse = WarehouseList::find($input['warehouse_id']);
		$products = ProductInventory::warehouses($warehouse->id);

		
		$pdf = App::make('dompdf.wrapper');
		$data = [
			'input' => $input,
			'products' => $products,
			'warehouse' => $warehouse,
		];

		$pdf->setPaper('A4');
		$pdf->getDomPDF()->set_option("enable_php", true);
		

		$pdf->loadView('Inventory.Reports.PhysicalCount.physical-count-report', $data);

		return $pdf->stream('PhysicalCount');
	}

}