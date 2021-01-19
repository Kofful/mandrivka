<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function add(Request $request) {
        if(!$request->daterange) {
            return null;
        }
        $application = Application::create([
            'price' => $request->price,
            'daterange' => $request->daterange,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'room_id' => $request->room_id
        ]);
        $daterange = explode(" ", str_replace(".", "/", $request->daterange));
        $dispatch1 = strtotime($daterange[0]);
        $dispatch2 = strtotime($daterange[2]);
        $dispatch1 = date("Y-m-d", $dispatch1);
        $dispatch2 = date("Y-m-d", $dispatch2);
        while($dispatch1 < $dispatch2) {
            Reservation::create([
                'reserved' => $dispatch1,
                'room_id' => $request->room_id
            ]);
            $dispatch1 = date("Y-m-d", strtotime($dispatch1 . "+1 day"));
        }
        return $application;
    }
}
