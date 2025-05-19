<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PinController extends Controller
{
    public function createPin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pin'        => 'required|digits:4',
            'verify_pin' => 'required|digits:4|same:pin',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 400);

        }

        $validatedData = $validator->validated();

        $user = User::where('id', auth()->user()->id)->first();

        if ($user->pin) {
            return response()->json([
                'status'  => false,
                'message' => 'PIN already created',
            ], 400);
        }

        $user->pin = Hash::make($request->pin);
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'PIN created successfully.']);
    }

    public function editPin(Request $request)
    {
        $request->validate([
            'old_pin'        => 'required|digits:4',
            'new_pin'        => 'required|digits:4',
            'verify_new_pin' => 'required|digits:4|same:new_pin',
        ]);

        $user = User::where('id', Auth::user()->id)->first();

        if (! Hash::check($request->old_pin, $user->pin)) {
            return response()->json(['message' => 'Old PIN is incorrect.'], 400);
        }

        $user->pin = Hash::make($request->new_pin);
        $user->save();

        return response()->json(['message' => 'PIN updated successfully.']);
    }
}
