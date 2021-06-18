<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon; 



class InventoryAdjustments extends Model {

    protected $table    = 'inventoryadjustments';
    
    protected $fillable = [
          'transaction_number',
          'date',
          'warehouse_id',
          'contact_person',
          'prepared_by',
          'source_document',
          'status',
          'type',
          'notes',
    ];
    
    public static $status_button = [
        'save_as_draft' => 0,
        'submit_for_approval' => 1,
        'validate' => 2,
        'decline' => 10,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'For Approval',
        2 => 'Adjusted',
        10 => 'Declined', 
    ];

    public static function boot()
    {
        parent::boot();

        InventoryAdjustments::observe(new UserActionsObserver);
    }
    
    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function details(){
        return $this->hasMany('App\Models\InventoryAdjustmentDetails', 'adjustment_id', 'id');
    }

    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'warehouse_id', 'date', 'prepared_by', 'status', 'id'];
            $orderby = $cols[5] . " DESC";

            $query = InventoryAdjustments::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
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
                $item->date = date("M d, Y", strtotime($item->date));
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
        $link = route('.inventoryadjustments.show',array($this->id));

        if($this->status == 0){
            $link = route('.inventoryadjustments.edit',array($this->id));
        }

        return $link;
        return $link;

    }

    
    public static function statusButton($button_name){
        return InventoryAdjustments::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }

}