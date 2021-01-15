<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchTourController extends Controller
{

    public function index()
    {
        return view('searchtour');
    }

    public function hot()
    {
        return view('searchtour');
    }
}
