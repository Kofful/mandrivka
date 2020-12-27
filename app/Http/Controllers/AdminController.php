<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin)
            return view('admin');
        return redirect('/');
    }
}
