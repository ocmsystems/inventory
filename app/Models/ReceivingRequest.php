<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;




class ReceivingRequest extends Model {

    protected $table    = 'receivingrequest';
    
    protected $fillable = [
          'project_name',
          'project_number',
          'client_name',
          'receiving_type',
          'warehouselist_id',
          'businessunit_id',
          'requested_date',
          'status',
          'prepared_by',
          'notes'
    ];
    
    public static $status_button = [
        'save_as_draft' => 0,
        'save_as_requested' => 1,
        'mark_as_scheduled' => 2,
        'pick_up_successful' => 3,
        'pick_up_unsuccessful' => 4,
        'mark_as_received' => 5,
        'save_only' => 6,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'Requested',
        2 => 'Scheduled',
        3 => 'Picked-up',
        4 => 'Pick-up Unsuccessful',
        5 => 'Goods Received',
    ];

    public static function boot()
    {
        parent::boot();

        ReceivingRequest::observe(new UserActionsObserver);
    }
    
    public function warehouselist()
    {
        return $this->hasOne('App\WarehouseList', 'id', 'warehouselist_id');
    }

    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function businessunit()
    {
        return $this->hasOne('App\Models\BusinessUnit', 'id', 'businessunit_id');
    }



    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'project_name', 'receiving_type', 'warehouselist_id', 'prepared_by', 'status'];
            $orderby = $cols[0] . " DESC";

            $query = ReceivingRequest::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
            if(auth()->user()->role->title != 'Administrator'){
                $query->where('prepared_by', auth()->user()->id);
            }

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
                $item->link = $item->link();
                $item->status = $item->statusValue();
                $item->receive_from = $item->warehouselist ? $item->warehouselist->name : '';
                $item->requested_by_name = $item->prepared_by_user ? $item->prepared_by_user->name : '';
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){
        $link = route('.receivingrequest.show',array($this->id));

        if($this->status == 0){
            $link = route('.receivingrequest.edit',array($this->id));
        }
        return $link;

    }
    
    public function details(){
        return $this->hasMany('App\Models\ReceivingRequestDetails', 'receivingrequest_id', 'id');
    }

    

    public static function statusButton($button_name){
        return ReceivingRequest::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }
}