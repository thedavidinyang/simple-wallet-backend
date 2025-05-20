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
                'status' => 'required|string',
                'message' => 'required|string',
                'transactionReference' => 'required|string',
                'paymentReference' => 'required|string',
                'authorizedAmount' => 'required|numeric',
            ]);


            $MONNIFY = (new MonnifyService);

            $verifyData =  $MONNIFY->verifyTransaction($request->transactionReference);

            if (isset($verifyData['paymentStatus']) && $verifyData['paymentStatus'] == 'PAID') {
                $user = auth()->user();

                $transaction = $user->transactions()->create([
                    'transaction_id' => $request->transactionReference,
                    'amount'         => $request->authorizedAmount,
                    'status'         => 'completed',
                    'type'           => 'credit',
                    'payment_reference' => $request->paymentReference,
                ]);


                    return response()->json([
                        'status'  => true,
                        'message' => 'Transaction succesful',
                    ], 200);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Could not verify transaction',
                ], 400);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'verify transaction failed',
                'errors'  => $e->getMessage(), 
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


    public function saveTransaction(Request $request)
    {
        try {
            $request->validate([
                'transaction' => 'required|string',
                'webhook'         => 'required|numeric',
                'amount'         => 'required|numeric',
            ]);

            $user = auth()->user();

            $transaction = $user->transactions()->create([
            
            ]);

            $user->wallet()->increment('balance', $request->amount);

            return response()->json([
                'status'  => true,
                'message' => 'Transaction saved successfully',
                'data'    => $transaction,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Save transaction failed',
                'errors'  => json_decode($e->getMessage(), true),
            ], 400);
        }
    }
    
}
