<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Models
 * */

use App\Models\Card;
use App\Models\CardAction;
use App\Models\Show;

/**
 * Traits
 */

use App\Traits\OutputTrait;
use Illuminate\Support\Facades\DB;

class CardsController extends Controller
{
    use OutputTrait;
    /**
     * cardsList
     *
     * @param  Card $card
     * @return void
     */
    public function cardsList(Request $request)
    {
        try {
            $id = Auth::id();
            $showid =   json_decode($request->show_id);
            $show =  Show::where('id', $showid)->get();
            $count = (50 / 100) * count($show);
            $int = round($count);
            $int = (int)$int;
            $in = Card::whereIn('show_id', $showid)->take($int)->get()->toarray();
            $not = Card::whereNotIn('show_id', $showid)->take($int)->inRandomOrder()->toarray();
            $output = array_merge($in, $not);
            $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $output);
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    public function validateCardAction()
    {
        return [
            'card_id' => ['required'],
            'card_action' => ['required'],
            'is_completed' => ['required']
        ];
    }

    /**
     * actionOnCard
     *
     * @param  Request $request
     * @param  CardAction $cardAction
     * @return void
     */
    public function actionOnCard(Request $request, CardAction $cardAction)
    {
        try {
            $this->validateRequest($request->all(), $this->validateCardAction());
            $arrData = $request->all();
            $arrData['user_id'] = Auth::user()->id;
            $arrData['card_id'] = $request->card_id;
            $arrData['card_action'] = $request->card_action;
            $getData = $cardAction->create($arrData);
            if ($request->is_completed == config('fieldstatus.active')) {
                // $getData = $cardAction->makeCompatibility(Auth::user()->id, $cardAction);
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"), $getData->toArray());
            } else {
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"));
            }
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    public function getMatch(Request $request)
    {
        try {
            $userid = Auth::id();
            $data = CardAction::where('user_id', $userid)->pluck('card_id');
            if ($data) {
                $same = DB::table('card_actions')->where('user_id', '!=', $userid)->whereIn('card_id', $data)->where('card_action', 1)->select('user_id')->get();

                if (empty($same)) {
                    return response()->json(['statuscode' => 200, 'message' => 'You Not like and cards'], 200);
                }
                $users =  $same->unique('user_id');
                $result = array();
                foreach ($users as $value) {
                    $new = DB::table('card_actions')->where('user_id', $value->user_id)->where('card_action', 1)->pluck('card_id');
                    $old = DB::table('card_actions')->where('user_id', $userid)->where('card_action', 1)->pluck('card_id');
                    $count = DB::table('card_actions')->whereIn('card_id', $data)->where('card_action', 1)->where('user_id', $value->user_id)->get();
                    $final = count($count) /  count($old) * 100;
                    $result =  DB::table('users')->where('id', $value->user_id)->first();
                    $ab[] =  array(
                        "id" => $result->id,
                        "name" => $result->name,
                        "email" => $result->email,
                        "mobile" => $result->mobile,
                        "full_name" => $result->full_name,
                        "date_of_birth" => $result->date_of_birth,
                        "profile_photo" => $result->profile_photo,
                        "about" => $result->about,
                        "latitude" => $result->latitude,
                        "longitude" => $result->longitude,
                        "laugh_id" => $result->laugh_id,
                        "matching_id" => $result->matching_id,
                        "show_id" => $result->show_id,
                        "age_range" => $result->age_range,
                        "max_distance" => $result->max_distance,
                        "ethnicity_preference" => $result->ethnicity_preference,
                        "gender" => $result->gender,
                        "user_type" => $result->user_type,
                        "age_preference_from" => $result->age_preference_from,
                        "age_preference_to" => $result->age_preference_to,
                        'percentage' => $final
                    );
                }
                return response()->json(['statuscode' => 200, 'message' => 'Get match list successfully ', 'data' => $ab], 200);
            }
            return response()->json(['statuscode' => 400, 'message' => 'Somethinkg Went Wrong '], 400);
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }
    public function getMatchcard(Request $request)
    {
        $userid = Auth::id();
        $user = DB::table('card_actions')->where('user_id', $userid)->where('card_action', 1)->pluck('card_id');
        $result = DB::table('card_actions')->whereIn('card_id', $user)->where('card_action', 1)->where('user_id', $request->user_id)->get();
        foreach ($result as $value) {
            $final[] = DB::table('cards')->where('id', $value->card_id)->first();
        }
        return response()->json(['statuscode' => 200, 'message' => 'Get card list successfully ', 'data' => $final], 200);
    }
}
