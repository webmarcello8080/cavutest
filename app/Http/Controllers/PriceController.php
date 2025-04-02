<?php

namespace App\Http\Controllers;

use App\Models\Price;

class PriceController extends Controller
{
    // return all the prices as JSON
    public function index()
    {
        $prices = Price::all();
        return response()->json($prices, 200);
    }
}
