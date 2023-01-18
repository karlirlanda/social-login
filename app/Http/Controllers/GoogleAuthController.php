<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callbackGoogle()
    {
        $google_user = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $google_user->id)->first();

        if(!$user){
            $new_user = User::create([
                'name' => $google_user->name,
                'email' => $google_user->email,
                'google_id' => $google_user->id,
            ]);

            Auth::login($new_user);

            return redirect()->intended('dashboard');   
        } else {
            Auth::login($user);

            return redirect()->intended('dashboard');
        }
    }
}
