<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;




class PullOuts extends Model {
    

    protected $table    = 'pullouts';
    
    protected $fillable = [
        'warehouse_id',
        'datetime_prepared',
        'source_document',
        'notes',
        'prepared_by',
        'pullout_date',
        'status'
    ];
    
    public static $status_button = [
        'save_as_draft' => 0,
        'submit_for_approval' => 1,
        'approve' => 2,
        'decline' => 3,
        'cancel' => 1,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'Requested',
        2 => 'Approved',
        3 => 'Declined',
    ];

    public static function boot()
    {
        parent::boot();

        PullOuts::observe(new UserActionsObserver);
    }
    
    
    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function details(){
        return $this->hasMany('App\Models\PullOutDetails', 'pullout_id', 'id');
    }


    public static function listing($input){
        $data = [];

        if(!empty($input)){


            $cols = ['transaction_number', 'prepared_by', 'warehouse_id', 'datetime_prepared', 'status'];
            $orderby = $cols[0] . " DESC";

            $query = PullOuts::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            
            if(!in_array('returns', auth()->user()->approval_modules()) && auth()->user()->role->title != 'Administrator' ){
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
                $item->warehouse_name = $item->warehouse->name;
                $item->link = $item->link();
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){
        $link = route('.pullouts.show',array($this->id));

        if($this->status == 0){
            $link = route('.pullouts.edit',array($this->id));
        }

        return $link;

    }

    public static function statusButton($button_name){
        return Returns::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }
}