<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplicationController extends Controller
{
    public function delete($id) {
        $application = Application::find($id);
        if($application) {
            $application->delete();
        } else {
            return null;
        }
        return $application;
    }
}
