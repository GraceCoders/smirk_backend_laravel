<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;
use Auth;
use Illuminate\Contracts\Validation\Validator as ValidationErrors;

/**
 * Models
 * */

use App\Models\Ethnicity;
use App\Models\Preference;
use App\Models\Laugh;
use App\Models\Matching;
use App\Models\Show;

/**
 * Traits
 */

use App\Traits\OutputTrait;

trait RetrieveTrait
{
    use OutputTrait;

    /**
     * retrieveList
     *
     * @param  Ethnicity $ethnicity
     * @param  Laugh $laugh
     * @param  Preference $preference
     * @param  Matching $matching
     * @return void
     */
    // public function retrieveList(Ethnicity $ethnicity, Laugh $laugh, Preference $preference, Matching $matching, Show $show)
    // {
    //     dd('asdasdsad');
    //     try {
    //         $getData['ethnicities'] = $ethnicity->where('status', config('fieldstatus.active'))->get();
    //         $getData['preferences'] = $preference->where('status', config('fieldstatus.active'))->get();
    //         $getData['laughs'] = $laugh->where('status', config('fieldstatus.active'))->get();
    //         $getData['matchings'] = $matching->where('status', config('fieldstatus.active'))->get();
    //         $getData['shows'] = $show->where('status', config('fieldstatus.active'))->get();
    //         $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $getData);
    //     } catch (Exception $exception) {
    //         $this->sendErrorOutput($exception);
    //     }
    // }

    /**
     * uploadPhoto
     *
     * @param  mixed $request
     * @return void
     */
    public function uploadPhoto(Request $request)
    {
        $path = Storage::disk('public')->put(config('filesdirectory.profile'), $request->file('profile_photo'));
        $filePath['profile_photo'] =  $path;
        $filePath['url'] = url('storage/' . $path);
        $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $filePath);
    }

    /**
     * generateToken
     *
     * @param  mixed $id
     * @return void
     */
    public function generateToken($id)
    {
        try {
            $http = new \GuzzleHttp\Client;

            $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config("settings.clientId"),
                    'client_secret' => config("settings.clientSecret"),
                    'username' => $id,
                    'password' => config("settings.defaultPassword"),
                    'scope' => '*',
                ],
            ]);


            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}