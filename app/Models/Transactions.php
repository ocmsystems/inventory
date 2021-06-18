<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;




class Transactions extends Model {

    protected $table    = 'transactions';
    
    protected $fillable = [
        'transaction_number',
        'product_id',
        'warehouse_id',
        'transaction_date',
        'prepared_by',
        'amount',
        'original_price',
        'discount',
        'quantity',
        'status',
    ];
    public $timestamps = false;

    
    protected $status_val = [
        1 => 'For Verification',
        2 => 'Verified',
        20 => 'Returned',
        10 => 'Declined', 
    ];
    

    public static $status_button = [
        'verify' => 2,
        'decline' => 10,
    ];
    
    public static function boot()
    {
        parent::boot();

        Transactions::observe(new UserActionsObserver);
    }
    
    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }

    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }

    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'transaction_date', 'warehouse_id', 'prepared_by', 'amount', 'status', 'id'];
            $orderby = $cols[0] . " DESC";

            $query = Transactions::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            
            if( !in_array('sales_transaction', auth()->user()->approval_modules()) && auth()->user()->role->title != 'Administrator' ){
                $query->where('prepared_by', auth()->user()->id);
            }
            
            $count = $query->count();
            $data['recordsTotal'] = $count;
            $data['recordsFiltered'] = $count;

            $query->orderByRaw( $orderby );
            if(isset($input['limit']) ){
                $query->take($input['limit']);
            }
            if(isset($input['start']) && !empty($input['start']) ){
                $query->skip($input['start']);
            }

            
            if(isset($input['search']) && !empty( $input['search']['value'] )){

            }

            $result = $query->get();

            foreach($result as $item){
                $item->transaction_date = date("M d, Y", strtotime($item->transaction_date));
                $item->warehouse_name = $item->warehouse->name;
                $item->prepared_by_name = $item->prepared_by_user->name;
                $item->link = $item->link();
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){
        $link = route('.transactions.show',array($this->id));

        return $link;
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }

    public static function statusButton($button_name){
        return Transactions::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public static function dashboard(){

        $data = [
            'labels' => [],
            'result' => [], 
            'data' => []
        ];

        $dates = [Carbon::now()->subDays(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')];

        $start_date = new \DateTime($dates[0], new \DateTimeZone('Asia/Manila')); 
        $end_date = new \DateTime($dates[1], new \DateTimeZone('Asia/Manila'));
		$period = new \DatePeriod(
			$start_date,
			new \DateInterval('P1D'),
			$end_date->modify('+1 day')
        );
		foreach ($period as $key => $value) {
			$data['result'][$value->format('M d')] = [
				'amount' => 0,
				'quantity' => 0,
            ];
            
            array_push($data['labels'], $value->format('M d'));
		}

        $query = Transactions::selectRaw('sum(quantity) as quantity, sum(amount) as amount, DATE_FORMAT(transaction_date, "%b %d") as transaction_date')
                                ->whereBetween('transaction_date', $dates)
                                ->where('status', 2)
                                ->groupBy('transaction_date');

        if( auth()->user()->role->title != 'Administrator' ){
            $query->where('prepared_by', auth()->user()->id);
        }

        $result = $query->get();

        foreach($result as $item){
            $data['result'][$item->transaction_date]['quantity'] = $item->quantity;
            $data['result'][$item->transaction_date]['amount'] = $item->amount;
        }

        foreach($data['result'] as $item){
            array_push($data['data'], $item['quantity']);
        }

        return $data;

        
    }
    
}