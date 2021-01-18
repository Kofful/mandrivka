<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Hotel;
use App\Models\Photo;
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
        $data = [
            'hotel' => [
                'hotel' => $hotel->hotel,
                'id' => $id,
                'country' => $hotel->state->country->country,
                'state' => $hotel->state->state,
                'photos' => $hotel->photos->toArray(),
                'description' => $hotel->description,

            ],
            'daterange' => $request->input('daterange'),
            ];
        return view('hotel', $data);
    }
}
