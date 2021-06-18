<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon; 



class Discounts extends Model {
    

    protected $table    = 'discounts';
    public $timestamps = false;
    
    protected $fillable = [
          'title',
          'warehouse_id',
          'start_datetime',
          'end_datetime',
          'prepared_by',
          'status',
    ];
    

    public static $status_button = [
        'submit' => 1,
        'inactive' => 2,
    ];
    
    protected $status_val = [
        1 => 'Active',
        2 => 'Inactive',
    ];

    public static function boot()
    {
        parent::boot();

        Deliveries::observe(new UserActionsObserver);
    }

    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function details(){
        return $this->hasMany('App\Models\DiscountDetails', 'discount_id', 'id');
    }

    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'delivery_date', 'source_warehouse_id', 'destination_warehouse_id', 'contact_person', 'status'];
            $orderby = $cols[0] . " DESC";

            $query = Discounts::with('warehouse')
                                ->selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
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
                $item->destination_warehouse_name = $item->destination_warehouse->name;
                $item->source_warehouse_name = $item->source_warehouse->name;
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function search($input){


    }

    
    public static function statusButton($button_name){
        return Deliveries::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }


    public static function dashboard(){

        $dates = [Carbon::now()->format('Y-m-d'), Carbon::now()->addDays(30)->format('Y-m-d')];

        $query = Discounts::with('warehouse')
                            ->whereBetween('start_datetime', $dates)
                            ->where('status', 1);

        
        if( auth()->user()->role->title != 'Administrator' ){
            $warehouse_arr = !empty(auth()->user()->warehouse_arr()) ? auth()->user()->warehouse_arr() : [0];
            $query->whereIn('warehouse_id', $warehouse_arr);
        }

        $result = $query->get();

        return $result;
    }
}