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
    protected function sendNotification($deviceIds, $action, $metaData, $fieldsArray)
    {
        $serverKey = 'AAAATnsMUec:APA91bH56g3z-1eE5zFFhc0DbHv7FJahX8NT293B_2aTRbZyxdwAno41RcYQTjCpT0KOi27r19Wrwx8bNHkAlqoOEFMgScR6ATlhtU9aYBY575yQlBN0Yjsm4o8KFJWT5yXGiXyuNZbA';
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];
        $fields = [];
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $fields = $fieldsArray;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, true));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    protected function requestNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = [
        //     'info' => $info
        // ];

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $fieldsArray);
        }
        $notification = [
            'notification_to' => $receiver,
            'notification_by' => $sender,
            'action' => $action,
            'message' => $message
        ];
        $this->insertNotification($notification);
    }

    public function insertNotification($notification)
    {
        Notification::create($notification);
    }

    public function getNotificationToken($user_id)
    {
        $devices = Device::where('user_id', $user_id)->get();
        if ($devices) {
            $tokens = [];
            foreach ($devices as $device) {
                $tokens = $device->notification_token;
            }
            return $tokens;
        }
        return [];
    }

    protected function bookingNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = [
        //     'info' => $info
        // ];

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $message);
        }
    }

    protected function ratingNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = $info;

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $message);
        }
        $notification = [
            'notification_to' => $receiver,
            'notification_by' => auth()->user()->id,
            'action' => $action,
            'message' => $message
        ];
        $this->insertNotification($notification);
    }

    protected function messageNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = $info;

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $message);
        }
    }

    protected function callNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = $info;

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $message);
        }
        $notification = [
            'notification_to' => $receiver,
            'notification_by' => auth()->user()->id,
            'action' => $action,
            'message' => $message
        ];
        $this->insertNotification($notification);
    }

    protected function connectionNotification($sender, $receiver, $action, $metaData, $message)
    {
        $deviceIds = $this->getNotificationToken($receiver);
        $fullName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $message = $message;
        $action = $action;
        // $metaData = $info;

        if ($deviceIds) {
            $fieldsArray = [];
            $fieldsArray = array(
                'registration_ids' => (array)$deviceIds,
                'priority' => 'high',
                'notification' => [
                    'body' => $message,
                    'title' => $fullName,
                ],
                'data' => [
                    'body' => $message,
                    'title' => $fullName,
                    'notification_type' => $action,
                    'data_payload' => $metaData
                ],
            );
            $this->sendNotification($deviceIds, $action, $metaData, $message);
        }
        $notification = [
            'notification_to' => $receiver,
            'notification_by' => auth()->user()->id,
            'action' => $action,
            'message' => $message
        ];
        $this->insertNotification($notification);
    }
}