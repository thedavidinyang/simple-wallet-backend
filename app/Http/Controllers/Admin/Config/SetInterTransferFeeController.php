<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;

class SetInterTransferFeeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:0',
        ]);

       $fee =  Configuration::updateOrCreate(['key' => 'interbank_transfer_fee'], ['value' => $request->amount]);

        return response()->json([
            'status' => true,
             'message' => 'Inter-Bank Transfer fee updated successfully!',
            'data' => [
                'key' => $fee->key,
                'value' => $fee->value
            ],
        ], 200);
    }
}
