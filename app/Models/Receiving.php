<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;




class Receiving extends Model {

    

    

    protected $table    = 'receiving';
    
    protected $fillable = [
          'transaction_number',
          'source_document',
          'received_date',
          'contact_person',
          'receiving_warehouse_id',
          'status',
          'prepared_by',
          'delivery_id',
          'notes',
    ];
    
    public static $status_button = [
        'save_as_draft' => 0,
        'submit_to_validate' => 1,
        'mark_as_received' => 2,
        'decline' => 10,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'For Validation',
        2 => 'Received',    
        10 => 'Declined', 
    ];

    public static function boot()
    {
        parent::boot();

        Receiving::observe(new UserActionsObserver);
    }
    
    public function receiving_warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'receiving_warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function delivery(){
        return $this->hasOne('App\Models\Deliveries', 'id', 'delivery_id');
    }

    public function details(){
        return $this->hasMany('App\Models\ReceivingDetails', 'receiving_id', 'id');
    }

    

    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'source_document', 'received_date', 'receiving_warehouse_id', 'prepared_by', 'status', 'id' ];
            $orderby = $cols[6] . " DESC";

            $query = Receiving::with('receiving_warehouse')
                                ->with('prepared_by_user')
                                ->selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            
            if(!in_array('receiving', auth()->user()->approval_modules()) && auth()->user()->role->title != 'Administrator'){
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
                $item->prepared_by_name = $item->prepared_by_user->name;
                $item->receiving_warehouse_name = $item->receiving_warehouse->name;
                $item->link = $item->link();
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){

        $link = route('.receiving.show',array($this->id));

        if($this->status == 0){
            $link = route('.receiving.edit',array($this->id));
        }

        return $link;

    }
    
    public static function statusButton($button_name){
        return Receiving::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }
}