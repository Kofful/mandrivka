<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{

    public function add(Request $request) {

        $state = State::create([
            'state' => $request->state,
            'country_id' => $request->country_id
        ]);
        return json_encode($state->toArray());
    }

    public function delete($id) {
        $state = State::find($id);
        if ($state) {
            $state->delete();
        } else {
            return null;
        }
        return json_encode($state->toArray());
    }
}
