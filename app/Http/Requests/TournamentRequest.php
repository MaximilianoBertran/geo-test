<?php

namespace App\Http\Requests;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;

class TournamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->banned) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:4',
            'gender_id' => 'required|integer|in:1,2',
            'players' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    $n = count($value);
                    while ($n != 1) {
                        if ($n % 2 != 0) {
                            $fail('The number of '.$attribute.' is invalid, please use a number of '.$attribute.' that is a power of 2.');
                            break;
                        }
                        $n = $n / 2;
                    }
                }
            ],
            'players.*.name' => 'required|min:4',
            'players.*.credential_code' => [
                'required',
                'min:4',
                'distinct',
                function ($attribute, $value, $fail) {
                    $player = Player::where('credential_code' ,$value)->where('gender_id', "!=", $this->gender_id)->first();
                    if ($player) {
                        $fail('The '.$attribute.' is register with another gender.');
                    }
                }
            ],
            'players.*.gender_id' => 'same:gender_id',
            'players.*.ability' => 'required|integer|between:0,100',
            'players.*.streng' => 'required|integer|between:1,10',
            'players.*.speed' => 'required|integer|between:1,10',
            'players.*.reaction' => 'required|integer|between:1,10'
        ];
    }
}
