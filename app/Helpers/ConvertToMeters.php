<?php
namespace App\Http\Helper;

class ConvertToMeters {

    public static function convert($lat1, $lon1, $lat2, $lon2){
        $R = 6378.137; // Radius of earth in KM
        $dLat = $lat2 * pi() / 180 - $lat1 * pi() / 180;
        $dLon = $lon2 * pi() / 180 - $lon1 * pi() / 180;
        $a = sin($dLat/2) * sin($dLat/2) +
            cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = $R * $c;
        if($d < 1000){
            return floor($d * 1000) . 'M'; // meters
        }
        return floor($d) . 'KM'; // KM
    }
}
