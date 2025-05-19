<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;


class CountryController extends Controller
{


    public function index()
    {

        try {

            $countries = Country::all();
            
            return response()->json([
                'status' => true,
                'message' => 'Countries successfully fetched',
                'data' => $countries,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
