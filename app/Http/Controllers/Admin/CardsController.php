<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Str;

/**
 * Models
 * */

use App\Models\Card;
use App\Models\Show;
use App\Traits\OutputTrait;

class CardsController extends Controller
{
    use OutputTrait;

    /**
     * cardsList
     *
     * @param  Request $request
     * @param  Card $card
     * @return void
     */
    public function cardsList(Request $request)
    {
        try {
          $card  = Card::join('shows','cards.show_id','=','shows.id')
          ->join('catgories','cards.category_id','=','catgories.id')
          ->where('cards.status',1)
          ->orderBy('cards.id','desc')
          ->select('cards.*','shows.title','catgories.name')
          ->paginate(10);
            return view('cards.list',compact('card'));
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * deletePreference
     *
     * @param  Request $request
     * @param  Card $card
     * @return void
     */
    public function deleteCard($id)
    {
        $user = Card::where('id',$id)->update(['status'=>0]); 
        return redirect('/cards/list')->with('success', 'Category Deleted Successfully');;

    }

    /**
     * addCard
     *
     * @param  Request $request
     * @param  Card $card
     * @return void
     */
    public function addCard(Request $request, Card $card)
    {
        try {
            $show  = Show::where('id',$request->show_id)->first();
            $formData = $request->all();
            unset($formData['_token']);

            if ($request->card_image) {
                $file =  $this->upload_file($request->card_image, 'card_image');
                $formData['card_image'] = $file;
            }
            $formData['category_id'] = $show->category_id;
            $adminDetail = $card->create($formData);
            if ($adminDetail) {
                return redirect('/cards/list')->with('success', 'Card added Successfully');;
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }
    public function editCard($id){
        $data = Card::join('shows','cards.show_id','=','shows.id')
        ->where('cards.id',$id)
        ->select('cards.*','shows.id as show_id','shows.title as show_name')
        ->first();
        return view('cards.edit',compact('data'));
    }
    public function updateCard(Request $request,$id){
      $data = Card::where('id',$id)->first();
      $show  = Show::where('id',$request->show_id)->first();
      if ($request->card_image) {
        $file =  $this->upload_file($request->card_image, 'card_image');
        $data->card_image =$file; 
    }else{
      $data->card_image =$data->card_image; 
    }
      $data->show_id = $request->show_id;
      $data->category_id = $show->category_id;
       $data->save();
       return redirect('/cards/list')->with('success', 'Card Updated Successfully');;
    }
}
