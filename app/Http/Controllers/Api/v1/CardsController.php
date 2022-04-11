<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BlockUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Models
 * */

use App\Models\Card;
use App\Models\CardAction;
use App\Models\GetMatch;
use App\Models\ProfileImage;
use App\Models\Show;
use App\Models\User;

/**
 * Traits
 */

use App\Traits\OutputTrait;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
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
            $show =  Card::whereIn('show_id', $showid)->count();
            $categoery =  Card::whereIn('show_id', $showid)->pluck('category_id');
            $count = (50 / 100) * $show;
            $int = round($count);
            $int = (int)$int;
            $data = CardAction::whereIn('show_id', $showid)->get();
            if (count($data) != 0) {
                $uid = CardAction::where('card_action',1)->pluck('card_id');
                $in = Card::whereIn('show_id', $showid)->whereIn('category_id', $categoery)->whereNotIn('id',$uid)->limit($int)->get()->toarray();
                $max = CardAction::where('card_action',1)->select('show_id', DB::raw('count(*) as total'))->groupBy('show_id')->orderBy('total', 'desc')->first();
                if ($max) {
                    $next = (50 / 100) * $int;
                    $int2 = round($next);
                    $int3 = (int)$int2;
                    $not = Card::whereNotIn('show_id', $showid)->whereNotIn('id',$uid)->where('show_id', $max->show_id)->limit($int3)->get()->toarray();
                    $all = Card::whereNotIn('show_id', $showid)->whereNotIn('id',$uid)->where('show_id', '!=', $max->show_id)->limit($int3)->inRandomOrder()->get()->toarray();
                    $output = array_merge($in, $not, $all);
                    $paginate = new Paginator($output,count($output), 10);
                    return response()->json(['statuscode' => 200, 'message' => 'ListedSuccessfully', 'data' => $paginate], 200);
                } else {
                    $all = Card::whereNotIn('show_id', $showid)->whereIn('category_id', $categoery)->limit($int)->paginate(10)->toarray();
                    $output = array_merge($in, $all);
                    $paginate = new Paginator($output,count($output), 10);
                    return response()->json(['statuscode' => 200, 'message' => 'ListedSuccessfully', 'data' => $paginate], 200);
                }
            } else {
                $output1 = Card::whereIn('show_id', $showid)->get();
               return response()->json(['statuscode' => 200, 'message' => 'ListedSuccessfully', 'data' => $output1], 200);
                }
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
            $id = Auth::id();
            $this->validateRequest($request->all(), $this->validateCardAction());
            $show =  CardAction::where('card_id', $request->card_id)->where('user_id',$id)->first();
            $shows =  Card::where('id', $request->card_id)->first();
            if(empty($show)){
            $arrData = $request->all();
            $arrData['user_id'] = $id;
            $arrData['card_id'] = $request->card_id;
            $arrData['card_action'] = $request->card_action;
            $arrData['show_id'] = $shows->id;
            $getData = $cardAction->create($arrData);
            if ($request->is_completed == config('fieldstatus.active')) {
                $getData1 = $cardAction->makeCompatibility($id, $cardAction);
                $exsit = GetMatch::where('user_id',$id)->where('match_with',$getData1['user']->id)->first();
                if(empty($exsit)){
                    $data = new GetMatch();
                    $data->user_id = $id;
                     $data->match_with = $getData1['user']->id;
                     $data->status = 1;
                     $data->save();
                }
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"), $getData1->toArray());
            } else {
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"), $getData->toArray());
            }
        }else{
            $this->sendSuccessResponse(trans("Messages.This Card Already liked"));

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
            if (count($data) != 0) {
                $block = BlockUser::where('blocked_by',$userid)->where('status',1)->pluck('user_id');
                $same = DB::table('card_actions')->where('user_id', '!=', $userid)->whereIn('card_id', $data)->whereNotIn('user_id',$block)->where('card_action', 1)->select('user_id')->get();
                if (empty($same)) {
                    return response()->json(['statuscode' => 200, 'message' => 'data not found'], 200);
                }
                $users =  $same->unique('user_id');
                $result = array();
                 if (count($users) != 0) {
                foreach ($users as $value) {
                   
                    $new = DB::table('card_actions')->where('user_id', $value->user_id)->where('card_action', 1)->pluck('card_id')->toArray();
                    $old = DB::table('card_actions')->where('user_id', $userid)->where('card_action', 1)->pluck('card_id')->toArray();
                    $count = DB::table('card_actions')->whereIn('card_id', $data)->where('card_action', 1)->where('user_id', $value->user_id)->get();
                    $final = count($count) /  count($old) * 100;
                    $result =  DB::table('users')->where('id', $value->user_id)->first();
                    $cards = array_intersect($new,$old);
                    $carddata = Card::whereIn('id',$cards)->get();
                    $usersdetail = ProfileImage::where('user_id',$value->user_id)->get();
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
                        'percentage' => $final,
                        'cards'=>$carddata,
                        'profileImage'=>$usersdetail,
                        'user'=>Auth::user()
                    );
                }
                return response()->json(['statuscode' => 200, 'message' => 'Get match list successfully ', 'data' => $ab], 200);
                 }else{
                    return response()->json(['statuscode' => 200, 'message' => 'no matches found'], 200); 
                 }
            }
            return response()->json(['statuscode' => 400, 'message' => 'Please like card'], 400);
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

