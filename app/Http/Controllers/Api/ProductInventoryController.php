<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\ProductInventory;
use App\WarehouseList;

class ProductInventoryController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
    
     public function get(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'results' => []
        ];

    
		$data['warehouse'] = WarehouseList::findOrfail($input['wid']);
        $data['products'] = ProductInventory::warehouses($data['warehouse']->id);
        

        header('Content-type: application/json');
        
        echo json_encode($data);
        exit();
     }


}