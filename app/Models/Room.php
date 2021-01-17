<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public static function getMain()
    {
        $tours = [];//обычные туры
        $hot_tours = [];//горящие туры
        $count = 0;
        while ($count < 8) {
            $nights = rand(3, 8);
            $dispatch1 = date("Y-m-d", strtotime("+" . rand(5, 30) . " day", strtotime('2020-06-07')));//TODO change to time() instead of date
            $dispatch2 = date("Y-m-d", strtotime($dispatch1 . "+$nights days"));
            $room = Room::inRandomOrder()->first();

            //проверка на статус "горящего" тура. Сначала ищутся 4 обычных, потом 4 горящих
            if($room->hotel->is_hot && $count >= 4 || !$room->hotel->is_hot && $count < 4) {
                continue;
            }
            //проверка, есть ли места
            if (($room->reservations
                ->where('reserved', '>', $dispatch1)
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
                $total_price += $transferTo->price + $transferFrom->price;
            }
            $tour = [
                'price' => $total_price,
                'places' => $room->places,
                'dispatch1' => $dispatch1,
                'dispatch2' => $dispatch2,
                'state' => $room->hotel->state->state,
                'country' => $room->hotel->state->country->country,
                'hotel' => $room->hotel->hotel,
                'nights' => $nights,
                'path' => $room->hotel->photos->first()->path
            ];
            $tour = array_merge($room->attributesToArray(), $tour);
            if($count < 4) {
                array_push($tours, $tour);
            } else {
                array_push($hot_tours, $tour);
            }
            $count++;
        }
        $result = [
            'tours' => $tours,
            'hot_tours' => $hot_tours];
        return $result;
    }

    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel');
    }

    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    public function roomType() {
        return $this->belongsTo('\App\Models\RoomType', 'type_id');
    }

}
