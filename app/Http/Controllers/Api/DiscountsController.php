<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Discounts;
use App\ProductList;
use App\Models\DiscountDetails;
use Validator;
use Carbon\Carbon; 

class DiscountsController extends Controller {


    public function add(Request $request){

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'warehouse_id' => 'required',
            'products.name.*' => 'required|integer|',
            'dates' => 'required',
            'products.discount.*' => 'required|integer|'
        ],
        [
            'warehouse_id' => 'The Warehouse field is required.',
            'products.name.*' => 'Products must be assigned.',
            'products.discount.*' => 'Discounts should be identified.'
        ]);

        if ($validator->passes()) {
            $dates = explode(" - ", $input['dates']);
            $input['start_datetime'] = Carbon::createFromFormat('M d, Y h:i A', $dates[0])->format('Y-m-d H:i:s');
            $input['end_datetime'] = Carbon::createFromFormat('M d, Y h:i A', $dates[1])->format('Y-m-d H:i:s');

            $input['status'] = 1;
            $input['prepared_by'] = auth()->user()->id;

		    $discounts = Discounts::create($input);

            $discounts->transaction_number = createTransactionNumber('DC', $discounts->id);
            $discounts->save();

    		if( isset($input['product']) && count($input['product']['name']) > 0 ){
                for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
                    $objDiscountDetails = new DiscountDetails();
                    $objProduct = ProductList::find($input['product']['name'][$ctr]);
                    if($objProduct){

                        $discount = $input['product']['discount'][$ctr];
                        $discount_type = $input['product']['discount_type'][$ctr];

                        if($discount_type == 1){
                            $discAmount = $objProduct->price * ( $discount / 100 );
                        }else{
                            $discAmount = $discount;
                        }
                        $discPrice = $objProduct->price - $discAmount;

                        $objDiscountDetails->discount_id = $discounts->id;
                        $objDiscountDetails->product_id = $objProduct->id;
                        $objDiscountDetails->original_price = $objProduct->price;
                        $objDiscountDetails->discount = $discount;
                        $objDiscountDetails->discount_type = $discount_type;
                        $objDiscountDetails->discounted_price = $discPrice;
                        $objDiscountDetails->save();
                    }

                }
            }

			return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }


    public function get(Request $request){
        $input = $request->all();

        $start_datetime = date('Y-m-d', strtotime($input['start']));
        $end_datetime = date('Y-m-d', strtotime($input['end']));

        $result = Discounts::with('warehouse')
                            ->with('details.product')
                            ->whereBetween('start_datetime', [$start_datetime, $end_datetime])
                            ->get();


        foreach($result as $item){

            $item->start = Carbon::createFromFormat('Y-m-d H:i:s', $item->start_datetime)->format("c");
            $item->end = Carbon::createFromFormat('Y-m-d H:i:s', $item->end_datetime)->format("c");
            if($item->status != 1){
                $item->backgroundColor = '#676363';
            }

        }

        echo json_encode($result);
        exit();

    }


    public function update($id, Request $request){
        $input = $request->all();
        $discounts = Discounts::find($id);

        if($discounts){

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'warehouse_id' => 'required',
                'dates' => 'required',
                'products.name.*' => 'required|integer|',
                'products.discount.*' => 'required|integer|'
            ],
            [
                'warehouse_id' => 'The Warehouse field is required.',
                'products.name.*' => 'Products must be assigned.',
                'products.discount.*' => 'Discounts should be identified.'
            ]);
    
            
            if ($validator->passes()) {
                $dates = explode(" - ", $input['dates']);
                $input['start_datetime'] = Carbon::createFromFormat('M d, Y h:i A', $dates[0])->format('Y-m-d H:i:s');
                $input['end_datetime'] = Carbon::createFromFormat('M d, Y h:i A', $dates[1])->format('Y-m-d H:i:s');

                $input['prepared_by'] = auth()->user()->id;
                if(!isset($input['status'])){
                    $input['status'] = 0;
                }
                $discounts->update($input);
			    $discounts->details()->delete();
                if( isset($input['product']) && count($input['product']['name']) > 0 ){
                    for($ctr = 0; $ctr < count($input['product']['name']); $ctr++){
                        $objDiscountDetails = new DiscountDetails();
                        $objProduct = ProductList::find($input['product']['name'][$ctr]);
                        if($objProduct){
    
                            $discount = $input['product']['discount'][$ctr];
                            $discount_type = $input['product']['discount_type'][$ctr];
    
                            if($discount_type == 1){
                                $discAmount = $objProduct->price * ( $discount / 100 );
                            }else{
                                $discAmount = $discount;
                            }
                            $discPrice = $objProduct->price - $discAmount;
    
                            $objDiscountDetails->discount_id = $discounts->id;
                            $objDiscountDetails->product_id = $objProduct->id;
                            $objDiscountDetails->original_price = $objProduct->price;
                            $objDiscountDetails->discount = $discount;
                            $objDiscountDetails->discount_type = $discount_type;
                            $objDiscountDetails->discounted_price = $discPrice;
                            $objDiscountDetails->save();
                        }
    
                    }
                }
    
                return response()->json(['success'=>'Discount successfully updated.']);
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        return response()->json(['error'=>['Invalid discount item.']]);
    }
}