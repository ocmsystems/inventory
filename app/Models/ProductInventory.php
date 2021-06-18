<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use App\Models\Approvers;
use Illuminate\Support\Facades\DB;


class ProductInventory extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'productinventory';
    public $timestamps = false;

    protected $fillable = [
          'product_id',
          'warehouse_id',
          'quantity',
          'reference',
          'reference_id',
          'type',
          'actual_qty',
    ];
    
    protected $models = [
        'inventoryadjustments' => 'InventoryAdjustments',
        'replenishments' => 'Replenishments',
        'deliveries' => 'Deliveries',
        'transactions' => 'Transactions',
        'receiving' => 'Receiving',
        'returns' => 'Returns',
        'pullouts' => 'PullOuts',
    ];

    public static function boot(){
        parent::boot();

        DeliveryDetails::observe(new UserActionsObserver);
    }

    
    public function product(){
        return $this->hasOne('App\ProductList', 'id', 'product_id');
    }
    
    public function warehouse(){
        return $this->hasOne('App\WarehouseList', 'id', 'warehouse_id');
    }
    

    public function getDelivered(){

        // $result = $this->
    }
    
    public function relLink(){
        if(!empty($this->reference_table)){
            $tes = 'App\Models\\' . $this->models[$this->reference_table];

            return $tes::find($this->reference_id);
        }else{
            return [];
        }

    }
    

    public static function warehouses($wid){

        $data = [];
        
        if(!empty($wid)){
            DB::enableQueryLog();
            $data = ProductInventory::from('productinventory as PI')
                        ->selectRaw('PL.barcode, PL.image, PL.name as product_name, PL.description as product_desc, sum(PI.actual_qty) as on_hand, PI.product_id, PI.warehouse_id, PL.reorder_quantity, PL.critical_quantity')
                        ->join('productlist as PL', 'PL.id', '=', 'PI.product_id')
                        ->where('PI.warehouse_id', $wid)
                        ->groupBy('PI.product_id', 'PI.warehouse_id')
                        ->get();
        }

        return $data;
    }
    

    public static function getCriticalItems(){

        
        $query = ProductInventory::from('productinventory as PI')
                        ->selectRaw('PL.image, PL.name as product_name, sum(PI.actual_qty) as on_hand, PL.critical_quantity, WL.name as warehouse_name')
                        ->join('productlist as PL', 'PL.id', '=', 'PI.product_id')
                        ->join('warehouselist as WL', 'WL.id', '=', 'PI.warehouse_id')
                        ->groupBy('PI.product_id', 'PI.warehouse_id')
                        ->orderBy('PI.warehouse_id')
                        ->havingRaw('PL.critical_quantity > sum(PI.actual_qty)');

        if( auth()->user()->role->title != 'Administrator' ){
            $warehouse_arr = !empty(auth()->user()->warehouse_arr()) ? auth()->user()->warehouse_arr() : [0];
            $query->whereIn('PI.warehouse_id', $warehouse_arr);
        }

        $result = $query->get();

        return $result;
    }

}