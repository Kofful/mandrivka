<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getStates(Request $request) {
        return Country::find($request->country)->states->toJson();
    }
}
