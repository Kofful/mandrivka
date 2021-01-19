<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function getTours(Request $request)
    {
        $daterange = explode(" ", str_replace(".", "/", $request->daterange));
        $dispatch1 = strtotime($daterange[0]);
        $dispatch2 = strtotime($daterange[2]);
        $dispatch1 = date("Y-m-d", $dispatch1);
        $dispatch2 = date("Y-m-d", $dispatch2);
        $interval = date_diff(date_create($dispatch1), date_create($dispatch2));//разница дней
        $nights = intval($interval->format("%a"));

        $pricerange = explode(" ", $request->pricerange);
        $min_price = intval($pricerange[0]);
        $max_price = intval($pricerange[2]);

        $places = 0;
        $min_age = 18;
        error_log($request);
        if (isset($request->children) && $request->children > 0) {
            $adults = $request->adults;
            $places += $adults;
            $half = 0;
            foreach ($request->child_ages as $childage) {
                if ($childage > 1) {
                    $places++;
                }
                if ($childage < $min_age) {
                    $min_age = $childage;
                }
            }
        } else {
            $places = $request->adults;
        }

        $tours = [];
        $hotels = Hotel::orderBy('id', 'DESC')->get();
        $count = 0;
        $page = $request->page * 10;
        foreach ($hotels as $hotel) {
            $rooms = $hotel->rooms;
            foreach ($rooms as $room) {
                if ($count >= $page + 10) {
                    break;
                }
                //выборка
                if ($request->hot && !$room->hotel->is_hot) {
                    continue;
                }
                if ($request->country != 0 && $request->country != $room->hotel->state->country_id) {
                    continue;
                }
                if ($request->state != 0 && $request->state != $room->hotel->state_id) {
                    continue;
                }
                if ($request->nutrition != 0 && $request->nutrition != $room->hotel->nutrition_id) {
                    continue;
                }
                if ($request->room_type != 0 && $request->room_type != $room->type_id) {
                    continue;
                }
                if ($places != $room->places) {
                    continue;
                }
                if ($min_age < $room->hotel->min_age) {
                    continue;
                }

                //проверка, есть ли места
                if (($room->reservations
                    ->where('reserved', '>=', $request->dispatch1)
                    ->where('reserved', '<', $dispatch2)
                    ->first())) {
                    continue;//не подходит, потому что занят в выбранный период
                }
                $total_price = $room->price * $nights;

                //проверка на страну. Поиск билетов, если это не Украина
                if ($room->hotel->state->country->id == 12) {//если Украина

                } else {
                    $transferFrom = $room->hotel->state->transfersFrom
                        ->where('dispatch', '=', $dispatch2)
                        ->where('state2_id', '=', '15')
                        ->first();
                    $transferTo = $room->hotel->state->transfersTo
                        ->where('dispatch', '=', $dispatch1)
                        ->where('state1_id', '=', '15')
                        ->first();
                    if (!$transferFrom || !$transferTo) {
                        continue;//нет билетов
                    }
                    $total_price += ($transferTo->price + $transferFrom->price) * $room->places;
                }
                if ($total_price > $max_price || $total_price < $min_price) {
                    continue;
                }
                if ($count >= $page) {
                    $tour = [
                        'room_type' => $room->roomType->type,
                        'price' => $total_price,
                        'places' => $room->places,
                        'state' => $room->hotel->state->state,
                        'country' => $room->hotel->state->country->country,
                        'hotel' => $room->hotel->hotel,
                        'description' => $room->hotel->description,
                        'nutrition' => $room->hotel->nutrition->name,
                        'nights' => $nights,
                        'path' => $room->hotel->photos->first()->path
                    ];
                    $tour = array_merge($room->attributesToArray(), $tour);
                    array_push($tours, $tour);
                    break;
                }
                $count++;
            }
        }
        return json_encode($tours);
    }

    public function add(Request $request) {
        $room = Room::create([
            'hotel_id' => $request->hotel_id,
            'type_id' => $request->type_id,
            'price' => $request->price,
            'places' => $request->places
        ]);
        return json_encode($room);
    }

    public function delete($id) {
        $room = Room::find($id);
        if ($room) {
            $room->delete();
        } else {
            return null;
        }
        return json_encode($room);
    }
}
