<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NotifyToken;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function  add_token (Request $request) {
       $data =  $request->validate([
           'device_type' => 'required',
            'token' => 'required'
        ]);
       if (auth()->check()){
           $data['user_id'] = auth()->user()->id;
       }

        $data = NotifyToken::query()->create($data);

        return response([
            'status' => true,
            'data' => $data,
//            'user_plan_status' => $user_plan_status,
            'msg' => ''
        ]);

    }
}
