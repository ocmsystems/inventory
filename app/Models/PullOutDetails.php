<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class PullOutDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'pullouts_details';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'qty',
          'note',
    ];
    

    public static function boot(){
        parent::boot();

        PullOutDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }
    
    
    
    
}