<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;
use Laraveldaily\Quickadmin\Models\Menu;


class ParentBuilder{

    // Global names
    private $name;
    private $parent_name = '';
    private $dir_parent_name = '';

    public function buildParent($name, $parent_id){
        $menu = Menu::find($parent_id);

        if(!empty($menu)){
            $this->parent_name = strtolower($menu->name);
            $this->dir_parent_name = ucfirst(Str::camel($this->parent_name)) . DIRECTORY_SEPARATOR;
        }

        $this->name = strtolower($name);

        $this->publish();
    }

    
    /**
     *  Publish directory into it's place
     */
    private function publish()
    {
        
        $camelName = ucfirst(Str::camel($this->name));
        
        if (! file_exists(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName))) {
            mkdir(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName));
            chmod(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName), 0775);
        }

        if (! file_exists(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName))) {
            mkdir(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName));
            chmod(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->dir_parent_name . $camelName), 0775);
        }

        // file_put_contents(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . $this->fileName), $template);
        // file_put_contents(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $this->fileName), $template);
    }

}