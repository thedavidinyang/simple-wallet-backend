<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\MonnifyService;

class Transaction extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function verify(Request $request)
    {

        $MONNIFY = (new MonnifyService());


        return $MONNIFY->monAuth();
    }
}
