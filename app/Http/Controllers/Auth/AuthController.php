<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Text in case of an error
     *
     * @var string
     */
    protected $failAuth = RouteServiceProvider::FAILAUTH;

    protected function getToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return $this->eApi($this->failAuth);
        }

        try {
            $password = User::where('email', $request->email)->first()->password;
        } catch (\Exception $e) {
            return $this->eApi($this->failAuth);
        }

        if (!Hash::check($request->password, $password)) {
            return $this->eApi($this->failAuth);
        }

        return $this->sApi();
    }
}
