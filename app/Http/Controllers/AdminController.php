<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Nutrition;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            $countries = Country::all()->toArray();
            $nutrition = Nutrition::all()->toArray();
            $data = [
                'countries' => $countries,
                'nutrition' => $nutrition
            ];
            return view('admin', $data);
        }
        return redirect('/');
    }
}
