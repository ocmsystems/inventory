<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon; 



class Deliveries extends Model {
    

    protected $table    = 'deliveries';
    
    protected $fillable = [
          'transaction_number',
          'destination_warehouse_id',
          'source_warehouse_id',
          'source_document',
          'delivery_date',
          'prepared_by',
          'contact_person',
          'status',
          'notes',
    ];
    

    public static $status_button = [
        'save_as_draft' => 0,
        'submit_for_approval' => 1,
        'approve' => 2,
        'mark_as_delivered' => 3,
        'decline' => 10,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'For Approval',
        2 => 'For Delivery',
        3 => 'Delivered',
        10 => 'Declined', 
    ];

    public static function boot()
    {
        parent::boot();

        Deliveries::observe(new UserActionsObserver);
    }
    
    
    public function destination_warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'destination_warehouse_id');
    }

    public function source_warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'source_warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function details(){
        return $this->hasMany('App\Models\DeliveryDetails', 'delivery_id', 'id');
    }

    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'delivery_date', 'source_warehouse_id', 'destination_warehouse_id', 'contact_person', 'status'];
            $orderby = $cols[0] . " DESC";

            $query = Deliveries::with('destination_warehouse')
                                ->with('source_warehouse')
                                ->selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            if(!in_array('deliveries', auth()->user()->approval_modules()) && auth()->user()->role->title != 'Administrator'){
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
                $item->destination_warehouse_name = !empty($item->destination_warehouse_id) ? $item->destination_warehouse->name : '';
                $item->source_warehouse_name = !empty($item->source_warehouse_id) ?  $item->source_warehouse->name : '';
                $item->link = $item->link();
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function search($input){


    }

    public function link(){

        $link = route('.deliveries.show',array($this->id));

        if($this->status == 0){
            $link = route('.deliveries.edit',array($this->id));
        }

        return $link;

    }
    
    public static function statusButton($button_name){
        return Deliveries::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }
}
