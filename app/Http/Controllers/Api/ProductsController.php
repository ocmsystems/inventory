<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\ProductList;
use App\Models\DiscountDetails;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller {

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
            'pagination' => ["more"=> false],
            'results' => []
        ];

        $objProductList = new ProductList();
        $output['results'] = $objProductList->search($input);

        echo json_encode($output);
        exit();
    }


    public function get_grouped(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'pagination' => ["more"=> false],
            'results' => []
        ];

        $objProductList = new ProductList();
        $output['results'] = $objProductList->search_group($input);
        
        echo json_encode($output);
        exit();
    }

    public function barcode(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'pagination' => ["more"=> false],
            'results' => []
        ];

        $item = ProductList::where('barcode', $input['barcode'])
                            ->get()->first();
        if( $item ){
            $item->original_price = $item->price;
            // DB::enableQueryLog();
            $discount = DiscountDetails::join('discounts', 'discounts.id', '=', 'discount_details.discount_id')
                            ->where('discount_details.product_id', $item->id)
                            ->where('discounts.status', 1)
                            ->where('discounts.warehouse_id', $input['warehouse_id'])
                            ->where('start_datetime', '<=', date("Y-m-d H:i:s"))
                            ->where('end_datetime', '>=', date("Y-m-d H:i:s"))
                            ->orderby('discounts.insert_datetime', 'desc')
                            ->get()->first();
            // pe(DB::getQueryLog());


            if( $discount > 0){
                $item->discount = $discount->discount;
                $item->original_price = $discount->original_price;
                $item->discounted_price = $discount->discounted_price;
            }

            $output['results'] = $item;
        }

        echo json_encode($output);
        exit();

    }

}