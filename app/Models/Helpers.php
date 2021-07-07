<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;




class Helpers extends Model {

    
    protected $table    = 'helpers';
    
    protected $fillable = [
          'name',
          'status'
    ];
    

    public static function boot()
    {
        parent::boot();

        Helpers::observe(new UserActionsObserver);
    }
    
    
    
    


    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['name', 'status', 'id'];
            $orderby = $cols[0] . " DESC";

            $query = Helpers::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
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
                $item->status = $item->status == 1 ? 'Yes' : 'No';
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function link(){
        $link = route('.helpers.edit',array($this->id));

        return $link;

    }
    
}