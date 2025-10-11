<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function getdata()
    {
        $cities = City::all();

        return response()->json($cities);

    }
}
