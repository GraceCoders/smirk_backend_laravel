<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChatUser;
use App\Models\Notification;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
     public function getRoom(Request $request){
        $id = Auth::id();
        $data = ChatUser::where('sender_id',$id)->orWhere('receiver_id',$id)->get();
        for($i=0;$i<count($data);$i++){
            $sender_id =   $data[$i]['sender_id'];
            $receiver_id = $data[$i]['receiver_id'];
            if($id != $sender_id){
                $user = User::where('id',$sender_id)->first();
                $profile = ProfileImage::where('user_id',$sender_id)->get();
            }else{
                $user = User::where('id',$receiver_id)->first();
                $profile = ProfileImage::where('user_id',$receiver_id)->get();
            }
            // $data[$i]['user_id']=$id;
            if($id == $sender_id){
               $data[$i]['user_id']=$data[$i]['receiver_id'];
                $data[$i]['receiver_id'] = $data[$i]['receiver_id'];
            }elseif($id == $receiver_id){
                $data[$i]['receiver_id']=$data[$i]['sender_id'];
                $data[$i]['user_id']=$data[$i]['sender_id'];
            }
            $data[$i]['user_name'] = $user->full_name;
            $data[$i]['profile_photo'] = $profile;
        }
        return response()->json(['statuscode' => 200, 'message' => 'chat list successfully', 'data' => $data], 200);
    }
    public function getNotification(Request $request){
        $id = Auth::id();
        $data = Notification::join('users','notification.send_by','=','users.id')->where('notification.user_id',$id)->select('notification.*','users.full_name')->get();
        for($i=0;$i<count($data);$i++){
                $profile = ProfileImage::where('user_id',$data[$i]->send_by)->first();
                $data[$i]['profile_photo'] = $profile->profile_pic;
                $data[$i]['created_at'] = $data[$i]->created_at->format('Y-m-d H:i:s');
        }
        return response()->json(['statuscode' => 200, 'message' => 'Get Notification list successfully', 'data' => $data], 200);
    }
}
