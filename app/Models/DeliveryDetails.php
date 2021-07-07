<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class DeliveryDetails extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'delivery_details';
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
        return $this->hasOne('App\Models\ProductList', 'id', 'product_id');
    }
    

    public function delivered_items(){
        $total = 0;

        $query = $this->selectRaw('sum(receiving_details.received_qty) as total')
                        ->join('receiving_details', 'receiving_details.delivery_details_id', '=', 'delivery_details.id')
                        ->join('receiving', 'receiving.id', '=', 'receiving_details.receiving_id')
                        ->where('receiving.status', 2)
                        ->where('delivery_details.id', $this->id);
        
        $result = $query->get();

        if ( !is_null( $result[0]->total ) ){
            $total = $result[0]->total;
        }
        return $total;
    }
    
    
    
}