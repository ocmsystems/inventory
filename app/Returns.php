<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Eloquent\SoftDeletes;

class Returns extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'returns';
    
    protected $fillable = [
          'productlist_id',
          'quantity',
          'warehouselist_id',
          'warehouselist_id',
          'datetime_created',
          'prepared_by',
          'source_document',
          'status'
    ];
    

    public static function boot()
    {
        parent::boot();

        Returns::observe(new UserActionsObserver);
    }
    
    public function productlist()
    {
        return $this->hasOne('App\ProductList', 'id', 'productlist_id');
    }


    public function warehouselist()
    {
        return $this->hasOne('App\WarehouseList', 'id', 'warehouselist_id');
    }

    
    


    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['id'];
            $orderby = $cols[0] . " DESC";

            $query = Returns::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
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
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){
        $link = route('.returns.edit',array($this->id));

        return $link;

    }
    
}