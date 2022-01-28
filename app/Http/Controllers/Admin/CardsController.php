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
    public function cardsList(Request $request, Card $card)
    {
        try {
            $formData = $request->all();
            $limit = $formData['length'];
            $offset = $formData['start'];
            $ethnicitiesList['draw'] = $formData['draw'];
            $userDetails['recordsTotal'] = $card->count();
            $userDetails['data'] = $card->offset($offset)->limit($limit)->get();
            $userDetails['recordsFiltered'] = $card->count();

            return json_encode($userDetails);
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
            $path = [];
            if ($request->hasFile('card_image')) {
                $storagePath = Storage::disk('public')->put('card_images', $request->file('card_image'));
                $path['card_image'] = url('storage/' . $storagePath);
            }
            $adminDetail = $card->create($path);
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