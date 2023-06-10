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
        try {
            return view('users.index');
        } catch (\Throwable $th) {

            return $th->getMessage();
        }
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
                'image' => 'mimes:jpeg,png,jpg,gif,svg'
                // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|size:1024'
            ], [
                // Custom message is writen here...
                'name.required' => 'Name field is required.',
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
            $userDetails->u_id = $user->id;
            $userDetails->mobile = $req->mobile;
            $userDetails->address = $req->address;

            if ($req->file('image')) {
                // return public_path();
                $imgDetails = $this->uploadImage($req->file('image'));
                $userDetails->image = $imgDetails['imageName'];

                // return $imgDetails;
            }

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

    public function uploadImage($file, $oldFile = null)
    {
        if ($oldFile) {
            unlink(public_path() . $oldFile);
        }
        $imageName = $file->getClientOriginalName();
        $mimesType = $file->getMimeType();
        $fileNewName = "/users/profile_pic/" . rand(100, 100000) . '_' . $imageName;
        $destinationPath = public_path() . "/users/profile_pic/";
        $file->move($destinationPath, $fileNewName);
        $fileData = [
            'imageName' => $fileNewName,
            'mimeType' => $mimesType
        ];
        return $fileData;
    }

    public function editModal(Request $req)
    {
        try {

            $userData = User::with('userDetails')->where('id', $req->userId)->first();

            $userEditModal = view('users.edit', compact('userData'))->render();

            return response()->json([
                'status' => true,
                'userEditModal' => $userEditModal
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function updateUser(Request $req)
    {

        try {

            $validation = Validator::make($req->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $req->userId,
                'mobile' => 'required|numeric',
                'image' => 'mimes:jpeg,png,jpg,gif,svg'
                // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|size:1024'
            ], [
                // Custom message is writen here...
                'name.required' => 'Name field is required.',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validation->errors()
                ]);
            }

            $user = User::find($req->userId);

            if ($user) {
                $user->name = $req->name;
                $user->email = $req->email;
            }
            $user->save();

            $userDetails = UserDetails::where('u_id', $req->userId)->first();
            if ($userDetails) {
                $userDetails->mobile = $req->mobile;
                $userDetails->address = $req->address;

                if ($req->file('image')) {
                    $imgDetails = $this->uploadImage($req->file('image'), $userDetails->image);
                    $userDetails->image = $imgDetails['imageName'];
                }

                $userDetails->save();
            }

            return response()->json([
                'status' => true,
                'success' => 'User data is updated successfully.'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'serverError' => $th->getMessage(),
            ]);
        }
    }

    public function deleteUser(Request $req)
    {
        try {
            $user = User::find($req->userId);
            if ($user)
                $user->delete();

            $userDetails = UserDetails::where('u_id', $req->userId)->first();

            if ($userDetails->image) {
                unlink(public_path() . $userDetails->image);
            }

            if ($userDetails)
                $userDetails->delete();

            return response()->json([
                'status' => true,
                'message' => 'User is delete successfully'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}