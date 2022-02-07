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
          $card  = Card::join('shows','cards.show_id','=','shows.id')->paginate(20);
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
    public function deleteCard(Request $request, Card $card)
    {
        if ($card->where('id', $request->id)->delete()) {
            return 'success';
        } else {
            return 'error';
        };
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
            if ($request->hasFile('card_image')) {
                $storagePath = Storage::disk('public')->put('card_images', $request->file('card_image'));
                $formData['card_image'] = url('storage/' . $storagePath);
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
