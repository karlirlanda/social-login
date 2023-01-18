<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function callbackFacebook()
    {
        $facebook_user = Socialite::driver('facebook')->stateless()->user();
        $user = User::where('facebook_id', $facebook_user->id)->first();

        if (!$user) {
            $new_user = User::create([
                'name' => $facebook_user->name,
                'email' => $facebook_user->email,
                'facebook_id' => $facebook_user->id,
            ]);

            Auth::login($new_user);

            return redirect()->intended('dashboard');
        } else {
            Auth::login($user);

            return redirect()->intended('dashboard');
        }
    }
}
