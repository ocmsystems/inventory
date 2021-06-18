<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Replenishments;

class ReplenishmentsController extends Controller {

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
            // 'input' => $input,
            'results' => []
        ];


        $objReplenishments = new Replenishments();
        $query = $objReplenishments->with('details.product')->selectRaw('id, transaction_number as text, destination_warehouse_id')
                                    ->where('status', 2)
                                    ->limit(10)
                                    ->orderBy('id', 'DESC');

        if(!empty($input['qry'])){
            $query->where('transaction_number','LIKE', "%".$input['qry']."%");
        }
                                   
        $output['results'] = $query->get();

        header('Content-type: application/json');
        
        echo json_encode($output);
        exit();
    }


}