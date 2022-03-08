<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Models
 * */

use App\Models\Show;
use App\Traits\OutputTrait;

class ShowsController extends Controller
{
    use OutputTrait;
    /**
     * showsList
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function showsList(Request $request, Show $show)
    {
        try {
            $show  = Show::join('catgories','shows.category_id','=','catgories.id')
            ->select('shows.*','catgories.name')
            ->paginate(10);
            return view('shows.list',compact('show'));
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * addShow
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function addShow(Request $request, Show $show)
    {
        try {
            $formData = $request->all();
            unset($formData['_token']);
            if ($request->show_icon) {
                $file =  $this->upload_file($request->show_icon, 'card_image');
                $formData['show_icon'] = $file;
            }
            $adminDetail = $show->create($formData);
            if ($adminDetail) {
                return redirect()->back()->with('success', 'Show added successfully');
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * deleteShow
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function deleteShow($id)
    {
        $user = Show::where('id',$id)->update(['status'=>0]); 
        return redirect('/shows/list')->with('success', 'Category Deleted Successfully');;

    }
    public function editShow($id){
       $show = Show::join('catgories','shows.category_id','=','catgories.id')
       ->select('shows.*','catgories.name')
       ->first();
       return view('shows.edit',compact('show'));
    }
    public function updateShow(Request $request,$id){
        $data = Show::where('id',$id)->first();
        if ($request->show_icon) {
          $file =  $this->upload_file($request->show_icon, 'show_icon');
      }
        $data->category_id =  isset($request->category_id) && !empty($request->category_id) ? $request->category_id : $data->category_id;
        $data->title =   isset($request->title) && !empty($request->title) ? $request->title : $data->title;
        if(!empty($file)){
          $data->show_icon =$file; 
        }else{
          $data->show_icon =$data->show_icon; 
        }
         $data->save();
         return redirect('/shows/list')->with('success', 'Card Updated Successfully');;
      }
}
