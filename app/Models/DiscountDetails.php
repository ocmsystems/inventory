<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class DiscountDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'discount_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'discount',
          'original_price',
          'discounted_price',
          'discount_type'
    ];
    

    public static function boot(){
        parent::boot();

        DiscountDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }
        
}