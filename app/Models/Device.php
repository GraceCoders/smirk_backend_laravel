<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'user_id',
        'notification_token',
        'device_type',
        'logged_in_at'
    ];

    /**
     * createOrUpdateDeviceToken
     *
     * @param  User $user
     * @param  array $arrData
     * @return void
     */
    public function createOrUpdateDeviceToken(User $user, array $arrData)
    {
        $objDevice = $this->where('notification_token', $arrData['notification_token'])->first();
        if ($objDevice) {
            $objDevice->user_id = $user->id;
            $objDevice->notification_token = $arrData['notification_token'] ?? $objDevice->notification_token;
            $objDevice->device_type = $arrData['device_type'] ?? $objDevice->device_type;
            $objDevice->logged_in_at = Carbon::now()->format("Y-m-d H:i:s");
        } else {
            $arrData = [
                'user_id' => $user->id,
                'device_id' => $arrData['device_id'] ? $arrData['device_id'] : '',
                'notification_token' => $arrData['notification_token'],
                'device_type' => $arrData['device_type'],
                'logged_in_at' => Carbon::now()->format("Y-m-d H:i:s")
            ];
            $this->fill($arrData)->save();
        }
    }

    public function logoutDevices(string $deviceId)
    {
        $this->where("device_id", $deviceId)->update(['user_id' => null, 'logged_in_at' => null]);
    }
}