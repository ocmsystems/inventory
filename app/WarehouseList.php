<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseList extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'warehouselist';
    
    protected $fillable = [
          'name',
          'short_name',
          'address',
          'contact_person',
          'type',
          'status',
    ];
    

    public static function boot()
    {
        parent::boot();

        WarehouseList::observe(new UserActionsObserver);
    }
    
    
    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['name', 'address', 'id'];
            $orderby = $cols[2] . " DESC";

            $query = WarehouseList::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
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
                $item->link = $item->link();
            }
            $data['data'] = $result;
        }

        return $data;
    }
    
    public function link(){
        $link = route('.warehouselist.edit',array($this->id));

        return $link;

    }

    public function personnel(){
        return $this->hasMany('App\Models\WarehousePersonnel', 'warehouse_id', 'id');
    }
    
    
}