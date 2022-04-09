<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChatUser;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getRoom(Request $request){
        $id = Auth::id();
        $data = ChatUser::where('sender_id',$id)->orWhere('receiver_id',$id)->get();
        for($i=0;$i<count($data);$i++){
            $sender_id = $data[$i]['sender_id'];
            $receiver_id = $data[$i]['receiver_id'];
            if($id != $sender_id){
                $user = User::where('id',$sender_id)->first();
            }else{
                $user = User::where('id',$receiver_id)->first();
            }
            $data[$i]['user_name'] = $user->full_name;
            $data[$i]['profile_photo'] = $user->profile_photo;
        }
        return response()->json(['statuscode' => 200, 'message' => 'chat list successfully', 'data' => $data], 200);
    }
    public function getNotification(Request $request){
        $id = Auth::id();
        $data = Notification::with('user')->where('user_id',$id)->get();
        return response()->json(['statuscode' => 200, 'message' => 'Get Notification list successfully', 'data' => $data], 200);
    }
}
