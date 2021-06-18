<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Pusher\Pusher;
use App\Events\TestPusherEvent;	


class Notifications extends Model {

    protected $table    = '';
    public $timestamps = false;



    public static function notify_approvers($params){
        
		$options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
            );

        $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'), 
                        $options
                    );

        $data['message'] = $params['message'];
        $data['link'] = $params['link'];
        $pusher->trigger($params['channel'], 'App\\Events\\TestPusherEvent', $data);
    }

}