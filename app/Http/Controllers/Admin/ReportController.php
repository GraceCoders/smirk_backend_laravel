<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportUser;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function index(Request $request){
        $data = ReportUser::with(['user','reportBy'])->paginate(10);
        return view('report.index',compact('data'));
    } 
    public function blockuser(Request $request){
        $user = User::where('id',$request->id)->first();
        if($user->status == 0){
           $data = User::where('id',$request->id)->update(['status'=>1]);
           return 'success';

        }else{
            $data = User::where('id',$request->id)->update(['status'=>0]);
            return 'Unblock';
        }
    
    }
}
