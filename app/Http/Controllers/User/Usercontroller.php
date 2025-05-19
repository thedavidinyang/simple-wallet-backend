<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Usercontroller extends Controller
{
    //

    public function getUserProfile(){


        $me = User::where('id', '=', auth()->user()->id)->first();

        return response()->json([
            'status'  => true,
            'message' => 'profile fetched successfully',
            'data' => $me,
        ], 200);

    }


        public function getWallet(){


        $user = auth()->user();

        return response()->json([
            'status'  => true,
            'message' => 'wallet fetched successfully',
            'data'    => $user->wallet,
        ], 200);
    }
}
