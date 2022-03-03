<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (! function_exists('getErrorAsString')) {
       function getErrorAsString($messagearr)
       {
           $message = '';
           if (is_string($messagearr)) {
               return $messagearr;
           }
           $totalmsg = $messagearr->count();
           foreach ($messagearr->all() as $key => $value) {
               $message .= $key < $totalmsg - 1 ? $value . '/' : $value;
           }
           return $message;
       }
     }
     if (! function_exists('push_notifications')) {
        function push_notifications($token,$title,$message,$type)
       {
           $url = 'https://fcm.googleapis.com/fcm/send';
           $fields = array(
               'to' => $token,
               'notification' => array('title' => $title, 'body' => $message ),
               'data' => array('title' => $title, 'body' => $message),
           );
           $headers = array(
               'Authorization:key=' . '',
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
    if (! function_exists('upload_file')) {

        function upload_file($file, $folder)
          {
          return  Storage::disk('public')->put($folder, $file);
        }
      }
      if (! function_exists('guard')) {
        function guard()
        {
         return Auth::guard('employee')->user();
        }
      }

