<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function add(Request $request) {

        $country = Country::create([
            'country' => $request->country
        ]);
        return json_encode($country->toArray());
    }

    public function delete($id) {
        $country = Country::find($id);
        if ($country) {
            $country->delete();
        } else {
            return null;
        }
        return json_encode($country->toArray());
    }

    public function getStates(Request $request) {
        if($request->country) {
            return Country::find($request->country)->states->toJson();
        }
    }
}
