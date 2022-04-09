<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Verified;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationErrors;
use Illuminate\Auth\Access\AuthorizationException;

use App\Models\Device;
use App\Models\Notification;

use Auth;


trait PushNotificationTrait
{
    protected function userNotification($user){
        
    }



    protected function push_notifications($token,$title,$message,$type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => $token,
            'notification' => array('title' => $title, 'body' => $message ),
            'data' => array('title' => $title, 'body' => $message),
        );
        $headers = array(
            'Authorization:key=' .'AAAAQj-Lo2o:APA91bGRoqgAGYebBrttnHFdvZqKDWsZYBCuXP7nMpAJn45xellNIF1LlkpZ_P0Nl1OgAi-z1h1qxsG5iWp3g6lSW8rqtnUlpfPPCZKDZ2B49qk6nrvv0QyVywJzmNipHhox1ZWIAAbf',
            'Content-Type:application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
