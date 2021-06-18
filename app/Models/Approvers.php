<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;



class Approvers extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'approvers';
    public $timestamps = false;

    public $modules = [
        'replenishments' => [
            'class' => 'Replenishments',
            'approval_status' => 1,
            'route' => 'replenishments'
        ],
        'inventoryadjustments' => [
            'class' => 'InventoryAdjustments',
            'approval_status' => 1,
            'route' => 'inventoryadjustments'
        ],
        'deliveries' => [
            'class' => 'Deliveries',
            'approval_status' => 1,
            'route' => 'deliveries'
        ],
        'sales_transaction' => [
            'class' => 'Transactions',
            'approval_status' => 1,
            'route' => 'transactions'
        ],
        'receiving' => [
            'class' => 'Receiving',
            'approval_status' => 1,
            'route' => 'receiving'
        ],
        'returns' => [
            'class' => 'Returns',
            'approval_status' => 1,
            'route' => 'returns'
        ],
        'pullouts' => [
            'class' => 'PullOuts',
            'approval_status' => 1,
            'route' => 'pullouts'
        ],
    ];

    protected $fillable = [
          'user_id',
          'module',
    ];
    

    public static function boot(){
        parent::boot();

        Approvers::observe(new UserActionsObserver);
    }
    
    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    
}