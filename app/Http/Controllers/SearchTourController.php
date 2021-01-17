<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Nutrition;
use App\Models\RoomType;
use Illuminate\Http\Request;

class SearchTourController extends Controller
{

    public function index()
    {
        $countries = Country::all()->toArray();
        $nutrition = Nutrition::all()->toArray();
        $room_types = RoomType::all()->toArray();
        $data = [
            'countries' => $countries,
            'nutrition' => $nutrition,
            'room_types' => $room_types
        ];
        return view('searchtour', $data);
    }
}
