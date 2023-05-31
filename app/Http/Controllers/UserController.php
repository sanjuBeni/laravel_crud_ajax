<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function index()
    {
        // echo '<pre>';
        // print_r(User::with('userDetails')->get());
        // die;
        return view('users.index');
    }
    function userTbl()
    {
        $userData = User::with('userDetails')->get();
        $userTbl = view('users.table', compact('userData'))->render();
        return response()->json([
            'status' => true,
            'userTbl' => $userTbl
        ]);
    }

    function saveUser(Request $req)
    {
        try {
            $validation = Validator::make($req->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => [
                    'required',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                    // ->uncompromised()
                ],
                'mobile' => 'required|numeric',
            ], [
                // Custom message is writen here...
                'name.required' => 'Name field is required.'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validation->errors()
                ]);
            }

            $user = new User;

            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = $req->password;
            $user->save();

            $userDetails = new UserDetails;
            $userDetails->user_id = $user->id;
            $userDetails->mobile = $req->mobile;
            $userDetails->address = $req->address;
            $userDetails->save();

            return response()->json([
                'status' => true,
                'success' => 'Data is store successfully.'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'serverError' => $th->getMessage(),
            ]);
        }
    }
}