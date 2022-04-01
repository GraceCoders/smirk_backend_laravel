<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\UserLike;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\BlockUser;
use App\Models\ReportUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\OutputTrait;


class UserLikeController extends Controller
{
    use OutputTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function likeUser(Request $request)
    {
        try {
            $data = new UserLike();
            $data->user_id = $request->user_id;
            $data->like = $request->like;
            $data->status = 1;
            $data->save();
            if ($request->like == 1) {
                return response()->json(['statuscode' => 200, 'message' => 'user like successfully', 'data' => $data], 200);
            } else {
                return response()->json(['statuscode' => 200, 'message' => 'user dislike successfully', 'data' => $data], 200);
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

    public function blockUserList(Request $request){
        $id = Auth::id();
        $data = BlockUser::with('user')->where('status',1)->where('blocked_by',$id)->paginate(20);
        return response()->json(['statuscode' => 200, 'message' => 'Get Block user list successfully', 'data' => $data], 200);

    }
    public function report(Request $request)
    {
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
