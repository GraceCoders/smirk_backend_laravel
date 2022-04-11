<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\UserLike;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\BlockUser;
use App\Models\ChatUser;
use App\Models\GetMatch;
use App\Models\Notification;
use App\Models\ReportUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\OutputTrait;
use App\Traits\PushNotificationTrait;
use Mockery\Matcher\Not;

class UserLikeController extends Controller
{
    use OutputTrait,PushNotificationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function likeUser(Request $request)
    {
        try { 
            $id = Auth::user();
            $title = "Smirk Notification";
            if($request->like == 1){
                $message = $id->full_name . "Like your profile";
            }else{
                $message = $id->full_name."DisLike your profile";
            }
            $type = 1;
            $user =  UserLike::where('user_id', $request->user_id)->where('likedBy', $id)->first();
            if (empty($user)) {
                $like = new UserLike();
                $like->user_id = $request->user_id;
                $like->like = $request->like;
                $like->status = 1;
                $like->likedBy = $id->id;
                $like->save();
                $sender =  UserLike::where('user_id',$id->id)->where('likedBy', $request->user_id)->where('like',1)->first();
                $receiver =  UserLike::where('user_id',$request->user_id)->where('likedBy', $id->id)->where('like',1)->first();
                $userdata = User::where('id',$request->user_id)->first();
                $this->push_notifications($userdata->device_token,$title,$message,$type);
                $notification = new Notification();
                $notification->message  =$message;
                $notification->type = $type;
                $notification->user_id= $request->user_id;
                $notification->send_by = $id->id;
                $notification->status = 1;
                $notification->save();
                if(!empty($sender) && !empty($receiver)){
                    if($sender->user_id ==  $receiver->likedBy){
                            $data = new ChatUser();
                            $data->sender_id = $request->user_id;
                            $data->receiver_id = $id->id;
                            $data->status = 1;
                            $data->save(); 
                      }
                }      
                if ($request->like == 1) {
                    $exsit = GetMatch::where('user_id',$id)->where('match_with',$request->user_id)->first();
                    if(empty($exsit)){
                        $data = new GetMatch();
                         $data->user_id = $id->id;
                         $data->match_with = $request->user_id;
                         $data->status = 1;
                         $data->save();
                    } 
                    return response()->json(['statuscode' => 200, 'message' => 'user like successfully', 'data' => $like], 200);
                }else{
                    return response()->json(['statuscode' => 200, 'message' => 'user dislike successfully', 'data' => $like], 200);
    
                }
            }else{
                $user->user_id = $request->user_id;
                $user->like = $request->like;
                $user->likedBy = $id->id;
                $user->save();
                $sender =  UserLike::where('user_id',$id->id)->where('likedBy', $request->user_id)->where('like',1)->first();
                $receiver =  UserLike::where('user_id',$request->user_id)->where('likedBy', $id->id)->where('like',1)->first();
                $userdata = User::where('id',$request->user_id)->first();
                $this->push_notifications($userdata->device_token,$title,$message,$type);
                if(!empty($sender) && !empty($receiver)){
                if($sender->user_id ==  $receiver->likedBy){
                    $ChatUser = ChatUser::where('receiver_id',$id->id)->where('sender_id',$request->user_id)->first();
                    if($ChatUser){
                        $data = new ChatUser();
                        $data->sender_id = $request->user_id;
                        $data->receiver_id = $id->id;
                        $data->status = 1;
                        $data->save(); 
                    } 
                }
            }
                if ($request->like == 1) {
                    $exsit = GetMatch::where('user_id',$id)->where('match_with',$request->user_id)->first();
                    if(empty($exsit)){
                        $data = new GetMatch();
                         $data->user_id = $id->id;
                         $data->match_with = $request->user_id;
                         $data->status = 1;
                         $data->save();
                    } 
                    return response()->json(['statuscode' => 200, 'message' => 'user like successfully', 'data' => $user], 200);
                }else{
                    return response()->json(['statuscode' => 200, 'message' => 'user dislike successfully', 'data' => $user], 200);
                }
            }
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }
    public function block(Request $request)
    {
        $id =  Auth::id();
        $this->validateRequest($request->all(), $this->validateLikeAction());
        $user =  BlockUser::where('user_id', $request->user_id)->where('blocked_by', $id)->first();
        if (empty($user)) {
            $block = new BlockUser();
            $block->user_id = $request->user_id;
            $block->status = $request->status;
            $block->blocked_by = $id;
            $block->save();
                return response()->json(['statuscode' => 200, 'message' => 'user Block successfully', 'data' => $block], 200);
        }else{
            $user->user_id = $request->user_id;
            $user->status = $request->status;
            $user->blocked_by = $id;
            $user->save();
            if ($request->status == 1) {
                return response()->json(['statuscode' => 200, 'message' => 'user Block successfully', 'data' => $user], 200);
            }else{
                return response()->json(['statuscode' => 200, 'message' => 'user Unblock successfully', 'data' => $user], 200);

            }
        }
    }
    public function validateLikeAction()
    {
        return [
            'status' => ['required'],
            'user_id' => ['required'],
        ];
    }
    public function validatereport(){
        return [
            'report' => ['required'],
            'user_id' => ['required'],
        ];
    }

    public function blockUserList(Request $request){
        $id = Auth::id();
        $data = BlockUser::with('user')->where('status',1)->where('blocked_by',$id)->paginate(20);
        return response()->json(['statuscode' => 200, 'message' => 'Get Block user list successfully', 'data' => $data], 200);

    }
    public function report(Request $request)
    {
        $this->validateRequest($request->all(), $this->validatereport());

        $id =  Auth::id();
        $user =  ReportUser::where('user_id', $request->user_id)->where('report_by', $id)->first();
        if (empty($user)) {
            $report = new ReportUser();
            $report->user_id = $request->user_id;
            $report->report = $request->report;
            $report->report_by = $id;
            $report->status = 1;
            $report->save();
                return response()->json(['statuscode' => 200, 'message' => 'Report added successfully', 'data' => $report], 200);
        }else{
            $user->user_id = $request->user_id;
            $user->report = $request->report;
            $user->report_by = $id;
            $user->status = 1;
            $user->save();
                return response()->json(['statuscode' => 200, 'message' => 'Report Updated successfully', 'data' => $user], 200);
        }
    }
}
