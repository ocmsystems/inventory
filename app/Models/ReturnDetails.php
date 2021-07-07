<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class ReturnDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'returns_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'qty',
    ];
    

    public static function boot(){
        parent::boot();

        ReturnDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\Models\ProductList', 'id', 'product_id');
    }
    
    
    
    
}