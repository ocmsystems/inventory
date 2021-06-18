<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon; 

use Illuminate\Database\Eloquent\SoftDeletes;

class Replenishments extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'replenishments';
    
    protected $fillable = [
          'contact_person',
          'scheduled_date',
          'destination_warehouse_id',
          'source_document',
          'notes',
          'priority',
          'prepared_by',
          'status'
    ];

    public static $status_button = [
        'save_as_draft' => 0,
        'submit_for_approval' => 1,
        'approve' => 2,
        'decline' => 3,
    ];
    
    protected $status_val = [
        0 => 'Draft',
        1 => 'Requested',
        2 => 'Approved',
        3 => 'Declined',
    ];

    protected $priority_val = [
        1 => 'Normal',
        2 => 'Urgent',
        3 => 'Very Urgent',
    ];

    public static function boot()
    {
        parent::boot();

        Replenishments::observe(new UserActionsObserver);
    }
    
    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['transaction_number', 'contact_person', 'destination_warehouse_id', 'scheduled_date', 'prepared_by', 'status'];
            $orderby = $cols[0] . " DESC";

            $query = Replenishments::with('destination_warehouse')
                          ->with('prepared_by_user')
                          ->selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            
            if(!in_array('replenishments', auth()->user()->approval_modules()) && auth()->user()->role->title != 'Administrator' ){
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
                // foreach($cols as $col){
                // $query->where(function($q) use ($input){
                //     $q->where('asar.item_id','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('a.title','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('s.name','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('c.name','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('asar.pageview','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('asar.date','LIKE', "%".$input['search']['value'] ."%");
                // });
                // // }
                // $data['recordsFiltered'] = $query->count();

            }

            $result = $query->get();

            foreach($result as $item){
                $item->prepared_by_name = $item->prepared_by_user->name;
                $item->destination_warehouse_name = $item->destination_warehouse->name;
                $item->link = $item->link();
                $item->status = $item->statusValue();
            }
            $data['data'] = $result;
        }

        return $data;
    }
    
    public function destination_warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'destination_warehouse_id');
    }
    
    public function prepared_by_user(){
        return $this->hasOne('App\User', 'id', 'prepared_by');
    }

    public function details(){
        return $this->hasMany('App\Models\ReplenishmentDetails', 'replenishment_id', 'id');
    }


    public function link(){
        $link = route('.replenishments.show',array($this->id));

        if($this->status == 0){
            $link = route('.replenishments.edit',array($this->id));
        }

        return $link;

    }
    

    public static function statusButton($button_name){
        return Replenishments::$status_button[ str_replace(" ", "_", strtolower($button_name)) ];
    }

    public function statusValue(){
        return $this->status_val[$this->status];
    }

    public function priorityValue(){
        return $this->priority_val[$this->priority];
    }

}