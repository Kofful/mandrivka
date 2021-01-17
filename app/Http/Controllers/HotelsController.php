<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    public function getHotels(Request $request) {
        $response = [];
        $page = $request->page * 10;
        $count = 0;
        $hotels = Hotel::orderBy('id', 'DESC')->get();
        foreach($hotels as $hotel) {
            if ($count >= $page + 10) {
                break;
            }
            if(isset($request->name) && stripos($hotel->hotel, $request->name) === false) {
                continue;
            }
            if ($request->country != 0 && $request->country != $hotel->state->country_id) {
                continue;
            }
            if ($request->state != 0 && $request->state != $hotel->state_id) {
                continue;
            }
            if ($count >= $page) {
                $result = [
                    'state' => $hotel->state->state,
                    'country' => $hotel->state->country->country,
                    'path' => $hotel->photos->first()->path
                ];
                $result = array_merge($hotel->attributesToArray(), $result);
                array_push($response, $result);
            }
            $count++;
        }
        return json_encode($response);
    }

    public function index()
    {
        $countries = Country::all()->toArray();
        $data = [
            'countries' => $countries,
        ];
        return view('hotels', $data);
    }
}
