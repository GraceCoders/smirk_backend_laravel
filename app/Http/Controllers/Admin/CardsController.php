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

class CardsController extends Controller
{
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
            $formData = $request->all();
            unset($formData['_token']);

            if ($request->card_image) {
                $file = upload_file($request->card_image, 'card_image');
                $formData['card_image'] = $file;
            }
            $adminDetail = $card->create($formData);
            if ($adminDetail) {
                return redirect()->back()->with('success', 'Card added successfully');
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }
}
