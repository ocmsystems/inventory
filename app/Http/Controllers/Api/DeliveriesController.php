<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;

class DeliveriesController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
    
     public function search(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'results' => []
        ];

    
        $objDeliveries = new Deliveries();
        $query = $objDeliveries->with('details.product')->selectRaw('deliveries.*, deliveries.transaction_number as text')
                                                ->where('status', 2)
                                                ->limit(10)
                                                ->orderBy('id', 'DESC');

        if(!empty($input['qry'])){
            $query->where('transaction_number','LIKE', "%".$input['qry']."%");
        }
                                    
        $results = $query->get();
        foreach($results as $item){
            foreach($item->details as $detail){

                $detail->delivered_qty = $detail->delivered_items();
            }
        }
        $output['results'] = $results;
        
        header('Content-type: application/json');
        
        echo json_encode($output);
        exit();
     }


}