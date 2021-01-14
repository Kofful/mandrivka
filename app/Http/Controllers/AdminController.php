<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        error_log(Room::getMain());
        if (Auth::user()->is_admin)
            return view('admin');
        return redirect('/');
    }
}
