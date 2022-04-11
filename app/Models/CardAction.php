<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'user_id',
        'card_action',
        'show_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * makeCompatibility
     *
     * @param  int $userId
     * @param  CardAction $cardAction
     * @return void
     */
    public function makeCompatibility($userId, CardAction $cardAction)
    {
        $likes = $cardAction->where('card_action', 1)->where('user_id', $userId)->get()->pluck('card_id');
        $disLikes = $cardAction->where('card_action', 0)->where('user_id', $userId)->get()->pluck('card_id');
        $cardActions = [];

        //Card compatibility Users
        $cardActions = $cardAction->where('user_id', '!=', $userId)->where(function ($query) use ($likes, $disLikes) {
            $query->Orwhere(function ($subQuery) use ($likes) {
                $subQuery->whereIn('card_id', $likes)->where('card_action', 1);
            });
            $query->Orwhere(function ($subQuery) use ($disLikes) {
                $subQuery->whereIn('card_id', $disLikes)->where('card_action', 0);
            });
        })->with('user')->groupBy('user_id')->first();

        //Cards Count
  
            $cardsCount = $cardAction->where('user_id', '!=', $userId)->where(function ($query) use ($likes, $disLikes) {
                $query->Orwhere(function ($subQuery) use ($likes) {
                    $subQuery->whereIn('card_id', $likes)->where('card_action', 1);
                });
                $query->Orwhere(function ($subQuery) use ($disLikes) {
                    $subQuery->whereIn('card_id', $disLikes)->where('card_action', 0);
                });
            })->groupBy('user_id')->first()->count();
            $cardActions->compatibility  = (($cardsCount / 10) * 100);

        return $cardActions;
    }
}
