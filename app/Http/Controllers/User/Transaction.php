<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\MonnifyService;
use Illuminate\Http\Request;

class Transaction extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function verify(Request $request)
    {

        try {
            $request->validate([
                'transaction_id' => 'required|string',
            ]);

            $MONNIFY = (new MonnifyService());

            return $MONNIFY->verifyTransaction($request->transaction_id);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'verify transaction failed',
                'errors'  => json_decode($e->getMessage(), true),
            ], 400);
        }
    }

    public function fetchTransaction(Request $request)
    {
        try {
           
            $user = auth()->user();

            $transactions = $user->transactions() ->paginate(10);

            return response()->json([
                'status'  => true,
                'message' => 'fetch transaction successful',
                'data'    => $transactions,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'fetch transaction failed',
                'errors'  => json_decode($e->getMessage(), true),
            ], 400);
        }
    }   
    
}
