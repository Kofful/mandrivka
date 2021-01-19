<?php

namespace App\Http\Controllers;

use App\Models\Application;
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
            $applications = Application::all();
            $applications_array = [];
            foreach ($applications as $application) {
                $temp = $application->toArray();
                $temp['country'] = $application->room->hotel->state->country->country;
                $temp['state'] = $application->room->hotel->state->state;
                $temp['hotel'] = $application->room->hotel->hotel;
                $temp['room'] = $application->room->roomType->type;
                array_push($applications_array, $temp);
            }
            $data = [
                'countries' => $countries,
                'nutrition' => $nutrition,
                'applications' => $applications_array
            ];
            return view('admin', $data);
        }
        return redirect('/');
    }
}
