<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\UserProfiles;
use App\ProfileImages;
use URL;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);
        $user = User::query()->where('email', $data['email'])->first();
        if (!$user) {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Email not recognised. Kindly reach us on support@rediscovertelevision.com with your query.'
            ]);
        }

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'password incorrect'
            ]);
        }
        // returning user profiles with user
        $userProfiles = UserProfiles::query()->where('user_id', $user->id)->get();
        $profiles=[];
        foreach($userProfiles as $userProfile){
            $profile_image = ProfileImages::where('id',$userProfile->image)->first();
            $userProfile->image = URL::to('upload/source/'.$profile_image->url);
            array_push($profiles,$userProfile);
        }
        return response([
            'status' => true,
            'data' => ['token' => $user->createToken($request->device ?? 'web')->plainTextToken, 'user' => $user, 'profiles' => $profiles],
            'msg' => 'Login successful'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Email or password incorrect'
            ]);
        }

        UserProfiles::where('user_id',$user->id)->delete();
        $user->delete();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response([
            'status' => true,
            'data' => [],
            'msg' => 'Successfully deleted your account information from our system'
        ]);
    }
}
