<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Payment;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required',
            'birth_date' => 'required',
        ]);

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->gender = $request['gender'];
        $user->birth_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('birth_date'));

        $user->save();

        $token = $user->createToken('SouthscapersApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('SouthscapersApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user_data = array(
            'id'        => Auth::user()->id,
            'name'      => Auth::user()->name,
            'email'     => Auth::user()->email,
            'gender'    => Auth::user()->gender,
            'birth_date'=> Auth::user()->birth_date,
            'address'   => Auth::user()->address,
            'img_avatar'=> base64_encode(Auth::user()->img_avatar),
        );
        return response()->json($user_data, 200);
    }

}
