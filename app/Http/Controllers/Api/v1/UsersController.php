<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Traits
 */

use App\Traits\OutputTrait;
use App\Traits\RetrieveTrait;
use App\Traits\TwilioTrait;

/**
 * Models
 */

use App\Models\User;
use App\Models\Device;
use App\Models\Image;
use App\Models\UserEthnicity;
use App\Models\UserPreference;
use App\Models\UserShow;
use App\Models\ProfileImage;
use App\Models\Question;
use Illuminate\Auth\Events\Registered;

class UsersController extends Controller
{
    use OutputTrait, RetrieveTrait, TwilioTrait;

    /**
     * validateSignUp
     *
     * @return void
     */
    private function validateLogin()
    {
        return [
            'mobile' => ['required', 'string'],
        ];
    }

    /**
     * login
     *
     * @param  Request $request
     * @param  User $user
     * @return void
     */
    public function login(Request $request, User $user)
    {
        try {
            $this->validateRequest($request->all(), $this->validateLogin());
            $otp = rand(1000, 9999);
            $this->sendSMS($request->mobile, $otp);
            $user = $user->where('mobile', $request->mobile)->first();
            if ($user) {
                $user->where('mobile', $request->mobile)->update(['otp' => $otp]);
            }
            $this->sendSuccessResponse(trans("Messages.OTPSent"));
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    public function validateOTP()
    {
        return [
            'mobile' => ['required', 'string'],
            'otp' => ['required', 'string']
        ];
    }

    /**
     * verifyOTP
     * Function will used for verify user account whether exist or not
     * @param  Request $request
     * @param  User $user
     * @return void
     */
    public function verifyOTP(Request $request, User $user)
    {
        try {
            $this->validateRequest($request->all(), $this->validateOTP());
            $user = $user->where('mobile', $request->mobile)->first();
            if (!$user) {
                $user['is_registered'] = config('fieldstatus.inactive');
                $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $user);
            } else {
                if ($user->otp == $request->otp) {
                    $user = $this->loginUser($user);
                } else {
                    throw new Exception(trans("Messages.WrongOTP"));
                }
                $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $user->toArray());
            }
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    /**
     * loginUser
     * Function used to create auth token and add/update device tokens in devices table
     * @param  User $user
     * @return User
     */
    public function loginUser(User $user): User
    {
        $request = request();
        $device = new Device();
        // $device->createOrUpdateDeviceToken($user, $request->only("device_id", "notification_token", "device_type"));
        $userDetails = User::getUserDetails($user->id);
        $userDetails->is_registered = $user->laugh_id != null ? config('fieldstatus.active') :
            config('fieldstatus.inactive');
        $userDetails->token = $user->createToken('SMIRK_AUTH_TOKEN')->plainTextToken;
        return $userDetails;
    }


    public function validateSignUp()
    {
        return [
            'mobile' => 'required',
            'date_of_birth' => 'required',
            'full_name' => 'required|string|max:256',
            'latitude' => 'required',
            'longitude' => 'required',
            'laugh_id' => 'required',
            'matching_id' => 'required',
            'gender' => 'required',
            'age_preference_to' => 'required',
            'age_preference_from' => 'required',
            'max_distance' => 'required',
            'preferences' => 'required|array',
            'profile_pic' => 'required|array',
            'shows' => 'required|array',
            'ethnicities' => 'required|array'
        ];
    }

    /**
     * signUp
     *
     * @param  Request $request
     * @param  User $user
     * @param  UserEthnicity $userEthnicity
     * @param  UserPreference $userPreference
     * @param  UserShow $userShow
     * @param  ProfileImage $profileImage
     * @return void
     */
    public function signUp(Request $request, User $user, UserEthnicity $userEthnicity, UserPreference $userPreference, UserShow $userShow, ProfileImage $profileImage)
    {
        try {
            $this->validateRequest($request->all(), $this->validateSignUp());
            $arrData = $request->all();
            $userId = $user->create(['mobile' => $request->mobile])->id;
            if (!empty($arrData['ethnicities'])) {
                $this->loopThrough($userId, $userEthnicity, $arrData['ethnicities'], config('tablecolumnname.ethnicity'));
                unset($arrData['ethnicities']);
            }
            if (!empty($arrData['preferences'])) {
                $this->loopThrough($userId, $userPreference, $arrData['preferences'], config('tablecolumnname.preference'));
                unset($arrData['preferences']);
            }
            if (!empty($arrData['shows'])) {
                $this->loopThrough($userId, $userShow, $arrData['shows'], config('tablecolumnname.show'));
                unset($arrData['shows']);
            }
            if (!empty($arrData['profile_pic'])) {
                $this->uploadImages($userId, $profileImage, $arrData['profile_pic']);
                unset($arrData['profile_pic']);
            }
            $user->where('id', $userId)->update($arrData);
            $user = $user->where('id', $userId)->first();
            $user = $this->loginUser($user);
            $this->sendSuccessResponse(trans("Messages.SignupSuccessful"), $user->toArray());
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    /**
     * uploadImages
     *
     * @param  object $tableName
     * @param  array $arrData
     * @return void
     */
    public function uploadImages(int $userId, $tableName, array $arrData)
    {
        foreach ($arrData as $arrayValue) {
            $path = Storage::disk('public')->put(config('filesdirectory.profile'), $arrayValue);
            $filePath =  url('storage/' . $path);
            $tableName->create(['user_id' => $userId, 'profile_pic' => $filePath]);
        }
    }

    /**
     * loopThrough
     *
     * @param  int $userId
     * @param  object $tableName
     * @param  array $arrData
     * @param  string $columnName
     * @return void
     */
    public function loopThrough(int $userId, $tableName, array $arrData, $columnName)
    {
        $tableName->where('user_id', $userId)->delete();
        foreach ($arrData as $arrayValue) {
            $tableName->create(['user_id' => $userId, $columnName => $arrayValue]);
        }
    }

    public function userDetail(Request $request, User $user)
    {
        try {
            $getResponse = User::getUserDetails($request->id);
            if (!$getResponse) {
                throw new Exception(trans("Messages.InvalidUser"));
            }
            $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $getResponse->toArray());
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    /**
     * updateProfile
     *
     * @param  Request $request
     * @param  User $user
     * @param  UserEthnicity $userEthnicity
     * @param  UserPreference $userPreference
     * @param  UserShow $userShow
     * @param  ProfileImage $profileImage
     * @return void
     */
    public function updateProfile(Request $request, User $user, UserEthnicity $userEthnicity, UserPreference $userPreference, UserShow $userShow, ProfileImage $profileImage)
    {
        try {
            $arrData = $request->all();
            $userId = Auth::user()->id;
            if (!empty($arrData['ethnicities'])) {
                $userEthnicity->where('user_id', $userId)->delete();
                $this->loopThrough($userId, $userEthnicity, $arrData['ethnicities'], config('tablecolumnname.ethnicity'));
                unset($arrData['ethnicities']);
            }
            if (!empty($arrData['preferences'])) {
                $userPreference->where('user_id', $userId)->delete();
                $this->loopThrough($userId, $userPreference, $arrData['preferences'], config('tablecolumnname.preference'));
                unset($arrData['preferences']);
            }
            if (!empty($arrData['shows'])) {
                $userShow->where('user_id', $userId)->delete();
                $this->loopThrough($userId, $userShow, $arrData['shows'], config('tablecolumnname.show'));
                unset($arrData['shows']);
            }
            if (!empty($arrData['profile_pic'])) {
                $profileImage->where('user_id', $userId)->delete();
                $this->uploadImages($userId, $profileImage, $arrData['profile_pic']);
                unset($arrData['profile_pic']);
            }
            $user->where('id', $userId)->update($arrData);
            $this->sendSuccessResponse(trans("Messages.UpdateSuccessful"));
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    /**
     * updateimage
     *
     * @param  Request $request
     * @param  User $user
     * @return void
     */
    public function updateImage(Request $request, User $user)
    {
        try {
            $path = Storage::disk('public')->put(config('filesdirectory.profile'), $request->file('profile_photo'));
            $filePath['profile_photo'] =  $path;
            $user->where('id', Auth::user()->id)->update($filePath);
            $this->sendSuccessResponse(trans("Messages.UpdateSuccessful"));
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    public function Question(Request $request)
    {
        $this->validateRequest($request->all(), $this->questionAction());

        $id = Auth::id();
        $data = new Question();
        $data->user_id = $id;
        $data->question = $request->question;
        $data->status = 1;
        $data->save();
        if ($data) {
            return response()->json(['statuscode' => 200, 'message' => 'Query Added Succssfully!', 'data' => $data], 200);
        } else {
            return response()->json(['statuscode' => 400, 'message' => 'something went wrong !'], 400);
        }
    }
    public function questionAction()
    {
        return [
        'question' =>'required'
      ];
    }
    public function updateImages(Request $request)
    {
        try {
            if (empty($request->profile_pic)) {
                return response()->json(['statuscode' => 400, 'message' => 'validation failed !', 'data' => 'The profile_pic field is required'], 400);
            }
            $Image = new Image();
            if ($request->profile_pic) {
                $file =  $this->upload_file($request->profile_pic, 'profile_pic');
                $Image->profile_pic = "https://smirkapp.us/storage/" . $file;
            }
            $Image->user_id =  Auth::id();
            $Image->save();
            $this->sendSuccessResponse(trans("Messages.UpdateSuccessful"), $Image->toArray());
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }
    public function deleteImage(Request $request)
    {
        $delete = Image::where('id', $request->id)->where('user_id', Auth::id())->delete();
        if ($delete) {
            return response()->json(['statuscode' => 200, 'message' => 'Image Deleted Successfully'], 200);
        } else {
            return response()->json(['statuscode' => 400, 'message' => 'Some thing went wrong'], 400);
        }
    }
}
