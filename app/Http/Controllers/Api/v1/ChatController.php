<?php

namespace App\Http\Controllers\Api\v1ßßß;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChatUser;

class ChatController extends Controller
{
    public function getRoom(Request $request){
        $data = ChatUser::with(['user','sender'])->get();
        return response()->json(['statuscode' => 200, 'message' => 'chat list successfully', 'data' => $data], 200);

    }
}
