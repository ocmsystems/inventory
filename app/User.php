<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Role;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Laraveldaily\Quickadmin\Traits\AdminPermissionsTrait;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, AdminPermissionsTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role_id', 'status', 'company', 'position', 'id_number', 'mobile_number'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function boot()
    {
        parent::boot();

        User::observe(new UserActionsObserver);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function approvals(){
        return $this->hasMany('App\Models\Approvers', 'user_id', 'id');
    }

    public function warehouses(){
        return $this->hasMany('App\Models\WarehousePersonnel', 'user_id', 'id')->orderBy('insert_datetime', 'desc');
    }

    

    public function approval_modules(){
        $modules = [];
        foreach($this->approvals as $module){
            array_push($modules, $module->module);
        }
        return $modules;
    }

    public function warehouse_arr(){
        $arr = [];

        foreach($this->warehouses as $stores){
            $arr[] = $stores->warehouse->id;
        }

        return $arr;
    }

    public function warehouselist(){
        $arr = [];

        foreach($this->warehouses as $stores){
            $arr[$stores->warehouse_id] = $stores->warehouse->name;
        }

        return collect($arr);

    }

    public function search_group($params){
        
        $query = $this->selectRaw('users.name, users.id, users.role_id, roles.title');
                        // ->where('productlist.status', 1)

            $query->join('roles', 'roles.id', '=', 'users.role_id');
            if(isset($params['search']) && !empty($params['search'] )){
                $query->where('users.name', 'like', '%' . $params['search'] . '%')
                        ->orWhere('roles.title', 'like', '%' . $params['search'] . '%');
            }

        $query->where('status', '=', 1);
        $result = $query->get();


        $data = [];
        $categories = [];
        $ctr = 0;
        foreach($result as $item){
            
            $prod_item = [
                'id' => $item['id'],
                'text' => $item['name'],
                'type' => 'user'
            ];

            if(!in_array($item['title'], $categories)){
                array_push($categories, $item['title']);

                $prod_item_cat = [
                    'id' => $item['role_id'],
                    'text' => $item['title'],
                    'type' => 'role'
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
    public function warehouse(){

        if( count($this->warehouses) > 0){
            return $this->warehouses[0]->warehouse_id;
        }else{
            return '';
        }

    }

    public function position_arr(){
        return $this->hasOne('App\Models\Positions', 'id', 'position');
    }

    public function company_arr(){
        return $this->hasOne('App\Models\Companies', 'id', 'company');
    }
}
