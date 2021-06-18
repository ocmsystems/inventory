<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class WarehousePersonnel extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'warehouse_personnel';
    public $timestamps = false;

    protected $fillable = [
          'user_id',
          'warehouse_id',
    ];
    

    public static function boot(){
        parent::boot();

        DiscountDetails::observe(new UserActionsObserver);
    }
    
    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    
    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }
    
    
}