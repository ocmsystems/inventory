<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class ReplenishmentDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'replenishments_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'requested_qty',
          'approved_qty'
    ];
    

    public static function boot(){
        parent::boot();

        ReplenishmentDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\Models\ProductList', 'id', 'product_id');
    }
    
    
    
    
}