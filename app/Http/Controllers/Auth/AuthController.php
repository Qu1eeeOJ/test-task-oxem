<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Text in case of an error
     *
     * @var string
     */
    protected $invalidToken = RouteServiceProvider::FAILAUTH;

    protected function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if (User::where('email', $request->email)->count() == 0) {
            return $this->eApi();
        }

        $password = User::where('email', $request->email)->first()->password;

        if (!Hash::check($request->password, $password)) {
            return $this->eApi();
        }

        return $this->sApi();
    }
}
