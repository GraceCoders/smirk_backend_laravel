<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\UserLike;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function likeUser(Request $request){
        try {
            $data = new UserLike();
            $data->user_id =$request->user_id;
            $data->like = $request->like;
            $data->status = 1;
            $data->save();
            if($request->like == 1){
                return response()->json(['statuscode' => 200, 'message' => 'user like successfully','data'=>$data], 200);
            }else{
                return response()->json(['statuscode' => 200, 'message' => 'user dislike successfully','data'=>$data], 200);
            }
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserLike  $userLike
     * @return \Illuminate\Http\Response
     */
    public function show(UserLike $userLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserLike  $userLike
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLike $userLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserLike  $userLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLike $userLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserLike  $userLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLike $userLike)
    {
        //
    }
}
