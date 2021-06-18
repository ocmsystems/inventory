<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller {

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
    
     public function search(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'results' => []
        ];

        $objUser = new User();
        
        $query = $objUser->selectRaw('name as text, id')
                        ->where('status', 1)
                        ->limit(10)
                        ->orderBy('id', 'DESC');

        if(!empty($input['qry'])){
            $query->where('name','LIKE', "%".$input['qry']."%");
        }
        if(isset($input['role'])){
            $query->where('role_id', $input['role']);
        }                            
        
        $results = $query->get();
        $output['results'] = $results;
        
        header('Content-type: application/json');
        
        echo json_encode($output);
        exit();
    }
    

     
    public function get_grouped(Request $request){
        $input = $request->all();
        $output = [
            'input' => $input,
            'pagination' => ["more"=> false],
            'results' => []
        ];

        $objUser = new User();
        $output['results'] = $objUser->search_group($input);
        
        echo json_encode($output);
        exit();
    }
}