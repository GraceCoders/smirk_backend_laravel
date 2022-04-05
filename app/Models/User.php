<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
        'otp',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function userShows()
    {
        return $this->hasMany(UserShow::class);
    }

    public function userPreferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function userEthinicities()
    {
        return $this->hasMany(UserEthnicity::class);
    }

    public function profileImage()
    {
        return $this->hasMany(ProfileImage::class);
    }

    public function laugh()
    {
        return $this->hasOne(Laugh::class, 'id', 'laugh_id');
    }

    public function matching()
    {
        return $this->hasOne(Matching::class, 'id', 'matching_id');
    }

    /**
     * signUpOrLogin
     *
     * @param  mixed $user
     * @param  mixed $arrData
     * @return void
     */
    public function signUpOrLogin(User $user, array $arrData)
    {
        $userData = $user->where('mobile', $arrData['mobile'])->first();
        if ($userData) {
            $user->where('mobile', $arrData['mobile'])->update(['otp' => $arrData['otp']]);
        } else {
            $user->fill($arrData)->save();
        }
        $userData = $user->where('mobile', $arrData['mobile'])->first();
        return $userData;
    }

    public static function getUserDetails(int $id): User
    {
        return self::where("id", $id)->with('laugh', 'matching', 'userShows', 'userShows.show', 'userPreferences', 'userPreferences.preference', 'userEthinicities', 'userEthinicities.ethnicity', 'profileImage')->first();
    }
}