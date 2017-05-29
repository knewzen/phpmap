<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo()
    {
        return '/@'.auth()->user()->username;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
//            'address' => 'required|max:255',
//            'city' => 'required|max:255',
//            'country' => 'required|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $referred_by = Cookie::get('referral');

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => str_slug($data['username'], '_'),
            'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'city' => $data['city'],
            'country' => $data['country'],
            'affiliate_id' => str_random(10),
            'referred_by'   => $referred_by,
            'is_verified' => false,
        ]);
    }
}
