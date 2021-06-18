<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategories extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'productcategories';
    
    protected $fillable = [
          'name',
          'description',
          'status'
    ];
    
    protected $status_val = [
        0 => 'In-active',
        1 => 'Active',
    ];

    public static function boot()
    {
        parent::boot();

        ProductCategories::observe(new UserActionsObserver);
    }
    
    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['name','id'];
            $orderby = $cols[0] . " DESC";

            $query = ProductCategories::selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
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
                // $item->image = '<img src="' . asset('uploads/thumb') . '/'.  $item->image . '" />';
                // $item->category_name = $item->productcategories->name;
                // $item->status = $item->statusValue();
                $item->link = $item->link();
            }
            $data['data'] = $result;
        }

        return $data;
    }
    
    
    public function link(){
        $link = route('.productcategories.edit',array($this->id));

        return $link;

    }
    
    public function statusValue(){
        return $this->status_val[$this->status];
    }
    
}