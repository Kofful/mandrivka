<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Hotel;
use App\Models\Nutrition;
use App\Models\Photo;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\State;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    public function getHotels(Request $request) {
        $response = [];
        $page = $request->page * 10;
        $count = 0;
        $hotels = Hotel::orderBy('id', 'DESC')->get();
        foreach($hotels as $hotel) {
            if ($count >= $page + 10) {
                break;
            }
            if(isset($request->name) && stripos($hotel->hotel, $request->name) === false) {
                continue;
            }
            if ($request->country != 0 && $request->country != $hotel->state->country_id) {
                continue;
            }
            if ($request->state != 0 && $request->state != $hotel->state_id) {
                continue;
            }
            if ($count >= $page) {
                $result = [
                    'state' => $hotel->state->state,
                    'country' => $hotel->state->country->country,
                    'path' => $hotel->photos->first()->path
                ];
                $result = array_merge($hotel->attributesToArray(), $result);
                array_push($response, $result);
            }
            $count++;
        }
        return json_encode($response);
    }

    public function loadImages(Request $request) {
        if (isset($_FILES)) {
            $result = "";
            foreach ($_FILES as $file) {
                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/';
                $filename = basename(str_replace(" ", "", str_replace(".", "", microtime())) . "." . explode("/", $file['type'])[1]);
                $uploadfile = $uploaddir . $filename;
                move_uploaded_file($file['tmp_name'], $uploadfile);
                $img = imagecreatefromjpeg($uploadfile);
                $width = imagesx($img);
                $height = imagesy($img);
                if($width / $height < 1.5) {
                    $new_height = round($width / 1.5);
                    $img = imagecrop($img, ['x' => 0, 'y' => round(($height - $new_height) / 2), 'width' => $width, 'height' => $new_height]);
                } else {
                    $new_width = round($height * 1.5);
                    $img = imagecrop($img, ['x' => round(($width - $new_width) / 2), 'y' => 0, 'width' => $new_width, 'height' => $height]);
                }
                imagejpeg($img, $uploadfile);
                $result .= base64_encode(file_get_contents($uploadfile));
                $result .= " ";
                imagedestroy($img);
                unlink($uploadfile);
            }
            return $result;
        }
    }

    public function add(Request $request) {
        $hotel = Hotel::create([
            'state_id' => $request->state_id,
            'hotel' => $request->hotel,
            'min_age' => $request->min_age,
            'nutrition_id' => $request->nutrition,
            'is_hot' => $request->hot,
            'description' => $request->description
        ]);
        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/';
        $total = sizeof($_FILES);
        $files = array();
        for ($i = 0; $i < $total; $i++) {
            $filename = basename(str_replace(" ", "", str_replace(".", "", microtime())) . "." . explode("/", $_FILES['photo' . $i]['type'])[1]);
            $files[$i] = $filename;
            $uploadfile = $uploaddir . $filename;
            move_uploaded_file($_FILES['photo' . $i]['tmp_name'], $uploadfile);
        }
        $photos = array();
        for ($i = 0; $i < $total; $i++) {
            $photos[$i] = Photo::create([
                'hotel_id' => $hotel->id,
                'path' => $files[$i]
            ]);
        }
        $hotel = $hotel->toArray();
        $hotel['photos'] = $photos;
        return json_encode($hotel);
    }

    public function index()
    {
        $countries = Country::all()->toArray();
        $data = [
            'countries' => $countries,
        ];
        return view('hotels', $data);
    }

    public function hotel($id, Request $request) {
        $hotel = Hotel::find($id);
        $hotel_rooms = $hotel->rooms;
        $room_types = RoomType::all()->toArray();
        $rooms = [];
        $empty_rooms = 0;
        foreach($hotel_rooms as $room) {
            $temp = [
                'room_type' => $room->roomType->type,
                'price' => $room->price,
                'places' => $room->places
            ];
            $reservations = Reservation::where('room_id', $room->id)->where('reserved', date('Y-m-d', time()))->get();
            if($reservations->isEmpty()) {
                $empty_rooms++;
            }
            $room = array_merge($room->attributesToArray(), $temp);
            array_push($rooms, $room);
        }
        $total_price = 0;
        $room = null;
        if($request->input('daterange')) {
            $daterange = explode(" ", str_replace(".", "/", $request->input('daterange')));
            $dispatch1 = strtotime($daterange[0]);
            $dispatch2 = strtotime($daterange[2]);
            $dispatch1 = date("Y-m-d", $dispatch1);
            $dispatch2 = date("Y-m-d", $dispatch2);
            $interval = date_diff(date_create($dispatch1), date_create($dispatch2));
            $nights = intval($interval->format("%a"));
            if($request->input('room_id')) {
                $room = Room::find($request->input('room_id'));
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
                    if ($transferFrom && $transferTo) {
                        $total_price += ($transferTo->price + $transferFrom->price) * $room->places;
                    }
                }
            }
        }

        $data = [
            'hotel' => [
                'hotel' => $hotel->hotel,
                'id' => $id,
                'country' => $hotel->state->country->country,
                'state' => $hotel->state->state,
                'nutrition' => $hotel->nutrition->name,
                'photos' => $hotel->photos->toArray(),
                'description' => $hotel->description,
                'rooms' => $rooms,
                'price' => $total_price,
                'empty_rooms' => $empty_rooms
            ],
            'room' => isset($room) ? $room->toArray() : [],
            'daterange' => $request->input('daterange'),
            'room_id' => $request->input('room_id'),
            'room_types' => $room_types
            ];
        return view('hotel', $data);
    }

    public function delete($id) {
        $hotel = Hotel::find($id);
        if ($hotel) {
            $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/';
            foreach ($hotel->photos->toArray() as $photo) {
                $uploadfile = $uploaddir . $photo['path'];
                unlink($uploadfile);
            }
            $hotel->delete();
        } else {
            return null;
        }
        return json_encode($hotel->toArray());
    }
}
