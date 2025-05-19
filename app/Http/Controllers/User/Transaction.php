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
                'message' => $e->getMessage(),
                'errors'  => $e->getMessage(),
            ], 400);
        }
    }
    
}
