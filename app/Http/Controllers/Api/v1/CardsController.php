<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Models
 * */

use App\Models\Card;
use App\Models\CardAction;

/**
 * Traits
 */

use App\Traits\OutputTrait;

class CardsController extends Controller
{
    use OutputTrait;
    /**
     * cardsList
     *
     * @param  Card $card
     * @return void
     */
    public function cardsList(Card $card)
    {
        try {
            $collection = collect($card->limit(10)->get());
            $getData = $collection->shuffle();
            $getData->all();
            $this->sendSuccessResponse(trans("Messages.ListedSuccessfully"), $getData->toArray());
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }

    public function validateCardAction()
    {
        return [
            'card_id' => ['required'],
            'card_action' => ['required'],
            'is_completed' => ['required']
        ];
    }

    /**
     * actionOnCard
     *
     * @param  Request $request
     * @param  CardAction $cardAction
     * @return void
     */
    public function actionOnCard(Request $request, CardAction $cardAction)
    {
        try {
            $this->validateRequest($request->all(), $this->validateCardAction());
            $arrData = $request->all();
            $arrData['user_id'] = Auth::user()->id;
            $arrData['card_id'] = $request->card_id;
            $arrData['card_action'] = $request->card_action;
            $getData = $cardAction->create($arrData);
            if ($request->is_completed == config('fieldstatus.active')) {
                $getData = $cardAction->makeCompatibility(Auth::user()->id, $cardAction);
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"), $getData->toArray());
            } else {
                $this->sendSuccessResponse(trans("Messages.cardActionSaved"));
            }
        } catch (Exception $exception) {
            $this->sendErrorOutput($exception);
        }
    }
}