<?php

namespace Laraveldaily\Quickadmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{

    // protected $primaryKey = 'menu_id'; 
    protected $fillable = [
        'position',
        'menu_type',
        'icon',
        'name',
        'title',
        'parent_id',
    ];

    public $relation_ids = [];

    /**
     * Convert name to ucfirst() and camelCase
     *
     * @param $input
     */
    public function setNameAttribute($input)
    {
        $this->attributes['name'] = ucfirst(Str::camel($input));
    }

    /**
     * Get children links
     * @return mixed
     */
    public function children(){
        return $this->hasMany('Laraveldaily\Quickadmin\Models\Menu', 'parent_id', 'id')->selectRaw('id, position, parent_id, menu_type, icon, name, title')->orderBy('position');
    }

    /**
     * Get links of children that are also a parent 
     * @return mixed
     */
    public function child_parent(){

        return $this->where('parent_id', $this->id)->where('menu_type', '2')->orderBy('position')->get();
    }

    /**
     * Get children links
     * @return mixed
     */
    public function get_children(){
        return $this->where('parent_id', $this->id)->orderBy('position')->get();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    public static function multiLevelMenus(){

        $menus = Menu::where('menu_type', '!=', 0)->where('parent_id', null)->get();

        foreach($menus as $menu){
            $submenus = Menu::where('parent_id', $menu->id)->get();
            if(!empty($submenus)){
                foreach($submenus as $submenu){
                    $itemmenus = Menu::where('parent_id', $submenu->id)->get();
                    if(!empty($itemmenus)){
                        $submenu->children = $itemmenus;
                    }
                }
                $menu->children = $submenus;
            }
        }

        return $menus;


    }

    public static function parentSelectArray($with_subparent = false){

        $menus = Menu::where('menu_type', 2)->select('title', 'id', 'parent_id')->where('parent_id', null)->get();

        $parent_arr = [];

        $parent_arr[null] = '-- no parent --';

        foreach($menus as $menu){
            $parent_arr[$menu->id] = $menu->title;

            if($with_subparent){
                $child_parent = $menu->child_parent();
                if(!empty($child_parent)){
                    foreach($child_parent as $child)
                    $parent_arr[$child->id] = "&nbsp;&nbsp;&nbsp;" . $child->title;
                }
            }

        }

        return $parent_arr;
    }

    public function availableForRole($role)
    {
        if ($role instanceof Role) {
            $role = $role->id;
        }

        if (! isset($this->relation_ids['roles'])) {
            $this->relation_ids['roles'] = $this->roles()->pluck('id')->flip()->all();
        }

        return isset($this->relation_ids['roles'][$role]);
    }
}
