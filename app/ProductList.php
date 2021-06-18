<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\DB;

use App\Models\ProductInventory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductList extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'productlist';
    
    protected $fillable = [
          'name',
          'description',
          'productcategories_id',
          'can_be_sold',
          'can_be_purchased',
          'image',
          'sku',
          'barcode',
          'price',
          'cost',
          'status',
          'reorder_quantity',
          'critical_quantity',
    ];
    
    protected $status_val = [
        0 => 'In-active',
        1 => 'Active',
    ];


    public static function boot()
    {
        parent::boot();

        ProductList::observe(new UserActionsObserver);
    }
    
    public function productcategories()
    {
        return $this->hasOne('App\ProductCategories', 'id', 'productcategories_id');
    }

    
    public static function listing($input){
        $data = [];

        if(!empty($input)){

            $cols = ['image', 'sku', 'name', 'description', 'productcategories_id', 'status', 'id'];
            $orderby = $cols[0] . " DESC";

            $query = ProductList::with('productcategories')
                          ->selectRaw( DB::raw( implode(",", $cols) . ', id' ) );
            
                          
            if(isset($input['order'][0])){
                $orderby = $cols[$input['order'][0]['column']] . " " . $input['order'][0]['dir'];
            }
            
            $count = $query->count();
            $data['recordsTotal'] = $count;
            $data['recordsFiltered'] = $count;

            $query->orderByRaw( $orderby );
            if(isset($input['limit']) ){
                $query->take($input['limit']);
            }
            if(isset($input['start']) && !empty($input['start']) ){
                $query->skip($input['start']);
            }

            
            if(isset($input['search']) && !empty( $input['search']['value'] )){
                // foreach($cols as $col){
                // $query->where(function($q) use ($input){
                //     $q->where('asar.item_id','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('a.title','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('s.name','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('c.name','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('asar.pageview','LIKE', "%".$input['search']['value'] ."%")
                //         ->orWhere('asar.date','LIKE', "%".$input['search']['value'] ."%");
                // });
                // // }
                // $data['recordsFiltered'] = $query->count();

            }

            $result = $query->get();

            foreach($result as $item){
                $item->image = '<img src="' . asset('uploads/thumb') . '/'.  $item->image . '" />';
                $item->category_name = $item->productcategories->name;
                $item->status = $item->statusValue();
                $item->link = $item->link();
            }
            $data['data'] = $result;
        }

        return $data;
    }

    public function search($params){

        
        $query = $this->selectRaw('productlist.name, CONCAT(productlist.name, " - ", productlist.description) as text, productlist.id, productlist.description, productlist.sku, productlist.price')
                        // ->where('productlist.status', 1)
                        ->limit(10);
        
        if(isset($params['cat'])){
            $query->join('productcategories', 'productcategories.id', '=', 'productlist.productcategories_id');
            $query->where('productcategories.id', $params['cat']);
        }

        if(isset($params['type']) && $params['type'] == 'inventory' && isset($params['warehouse'])){

            if(isset($params['qty_type']) && $params['qty_type'] == 1){
                $query->join('productinventory', 'productinventory.product_id', '=', 'productlist.id');
                $query->where('productinventory.warehouse_id', $params['warehouse']);
                $query->groupBy('productinventory.product_id');
                $query->addSelect(DB::raw('sum(productinventory.actual_qty) as on_hand') );
            }else{
                $query->addSelect(DB::raw('0 as on_hand') );
            }
        }

        if(isset($params['type']) && $params['type'] == 'check'){
            $query->join('productinventory', 'productinventory.product_id', '=', 'productlist.id');
            $query->join('warehouselist', 'warehouselist.id', '=', 'productinventory.id');
            $query->groupBy('productinventory.product_id');

            $query->addSelect(DB::raw('sum(productinventory.actual_qty) as on_hand, warehouselist.name as warehouse_name') );

            $query->havingRaw('SUM(productinventory.actual_qty) > 0');
            $query->where('productlist.name', $params['product_name']);
        }

        if(isset($params['search']) && !empty($params['search'] )){
            $query->where('productlist.name', 'like', '%' . $params['search'] . '%');
            $query->orWhere('productlist.description', 'like', '%' . $params['search'] . '%');
        }

        if(isset($params['barcode']) && !empty($params['barcode'] )){
            $query->where('productlist.barcode', $params['barcode']);
        }

        $result = $query->get();
        // $data = [];
        if(isset($params['cat'])){
            foreach($result as $item){
                $item['product'] = ['name' => $item['description']];
                $item['original_price'] = $item['price'];
                $item['discounted_price'] = $item['price'];
                $item['discount'] = 0;
                $item['product_id'] = $item['id'];
            }
        }

        return $result;
    }

    public function search_group($params){

        
        $query = $this->selectRaw('productcategories.name as cat_name, productcategories.id as cat_id, productlist.name, productlist.name as text, productlist.id, productlist.description, productlist.sku, productlist.price');
                        // ->where('productlist.status', 1)

            $query->join('productcategories', 'productcategories.id', '=', 'productlist.productcategories_id');
            if(isset($params['search']) && !empty($params['search'] )){
                $query->where('productcategories.name', 'like', '%' . $params['search'] . '%')
                        ->orWhere('productlist.description', 'like', '%' . $params['search'] . '%');
            }

        $result = $query->get();


        $data = [];
        $categories = [];
        $ctr = 0;
        foreach($result as $item){
            
            $prod_item = [
                'id' => $item['id'],
                'text' => $item['description'],
                'price' => $item['price'],
                'name' => $item['name'],
                'sku' => $item['sku'],
                'type' => 'product'
            ];

            if(!in_array($item['cat_name'], $categories)){
                array_push($categories, $item['cat_name']);

                $prod_item_cat = [
                    'id' => $item['cat_id'],
                    'text' => $item['cat_name'],
                    'type' => 'cat'
                ];
                array_push($data, $prod_item_cat);

                // $data[$ctr] = [
                //     'id' => $item['cat_id'],
                //     'text' => $item['cat_name'],
                //     'children' => []
                // ];

                // array_push($data[$ctr]['children'], $prod_item);
                array_push($data, $prod_item);
                $ctr++;
            }else{
                // array_push($data[($ctr-1)]['children'], $prod_item);
                
                array_push($data, $prod_item);
            }
            // p($item);
        }

        return $data;
    }

    public function link(){
        $link = route('.productlist.edit',array($this->id));

        return $link;

    }

    public function on_hand($warehouse_id){

        $result = ProductInventory::selectRaw('sum(actual_qty) as on_hand')
                                ->where('warehouse_id', $warehouse_id)
                                ->where('product_id', $this->id)
                                ->groupBy('product_id')
                                ->get()->first();
        if($result){
            return $result->on_hand;
        }else{
            return 0;
        }
    }
    
    public function statusValue(){
        return $this->status_val[$this->status];
    }
    
    
    
}