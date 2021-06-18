<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class ReceivingDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'receiving_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'received_qty',
    ];
    

    public static function boot(){
        parent::boot();

        ReceivingDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }

    public function dr_details(){
        return $this->hasOne('App\Models\DeliveryDetails', 'id', 'delivery_details_id');
    }
    
    public function receiving(){
        return $this->belongsTo('App\Models\Receiving', 'receiving_id', 'id');
    }
    
    
    
}