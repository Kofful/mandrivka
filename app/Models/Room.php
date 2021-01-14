<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Room extends Model
{
    use HasFactory;

    public static function getMain()
    {
        $nights = rand(3, 8);
        $dispatch1 = date("Y-m-d", strtotime("+" . rand(5, 30) . " day", time()));
        $dispatch2 = date("Y-m-d", strtotime($dispatch1 . "+$nights days"));
        $date_to_check_reservations = date('Y-m-d', strtotime($dispatch2 . ' +1 days'));
        $room = Room::inRandomOrder()->first();
        $total_price = $room->price * $nights;
        $result = [
            $total_price
        ];
        if($room->hotel->state->country->id == 12) {//если украина

        } else {
            //$room->hotel->state->flights//нормализованная таблица
        }
        error_log($room->hotel->state->country->country);
        $result = DB::table('rooms')
            ->select('rooms.id',
                'rooms.hotel_id',
                DB::raw('room_types.type AS room_type'),
                'rooms.places',
                DB::raw('(rooms.price * 7 * 1+ IFNULL(SUM(transfers.price), 0)) AS price, COUNT(transfers.id) AS tickets'),
                'hotels.hotel',
                'hotels.description',
                DB::raw('nutrition.name AS nutrition'),
                'states.state',
                'countries.country',
                'photos.path')
            ->join('hotels', 'hotels.id', '=', 'hotel_id')
            ->join('states', 'states.id', '=', 'hotels.state_id')
            ->join('countries', 'countries.id', '=', 'states.country_id')
            ->join('photos', 'photos.id', '=', DB::raw('(SELECT id FROM photos WHERE hotel_id = hotels.id LIMIT 1)'))
//            ->leftJoin('transfers', function($join) {
//                $join->on(DB::raw('(transfers.state1_id = 15
//                AND transfers.state2_id = states.id
//                AND transfers.dispatch = \'2020-09-05\')'));
//                $join->orOn(DB::raw('(transfers.state1_id = states.id
//                AND transfers.state2_id = 15
//                AND transfers.dispatch = \'2020-09-12\')'));
//            })
            ->leftJoin('transfers', DB::raw('(transfers.state1_id = 15
                AND transfers.state2_id = states.id
                AND transfers.dispatch = \'2020-09-05\') OR (transfers.state1_id = states.id
                AND transfers.state2_id = 15
                AND transfers.dispatch = \'2020-09-12\')'))
            ->leftJoin('nutrition', 'nutrition.id', '=', 'hotels.nutrition_id')
            ->join('room_types', 'room_types.id', '=', 'rooms.type_id')
            ->groupBy('hotel_id')
            ->havingRaw('tickets = 0 OR tickets = 2')
            ->toSql();
        return $result;
    }

    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel');
    }
}
