<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Show;
use Illuminate\Http\Request;

class ShowsController extends Controller
{
    public function getShow()
    {
        $show = Show::get();
        return response()->json(['statuscode'=>200,'message'=>'Get show successfully','data'=>$show],200);
    }
}
