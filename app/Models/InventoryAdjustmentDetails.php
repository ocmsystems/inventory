<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class InventoryAdjustmentDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'inventoryadjustment_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'requested_qty',
          'approved_qty'
    ];
    

    public static function boot(){
        parent::boot();

        DeliveryDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }
    
    
    
}