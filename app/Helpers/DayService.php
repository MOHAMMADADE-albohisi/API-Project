<?php
namespace App\Helpers;

use App\Models\DayTranslation;
use App\Models\Language;

class DayService
{
   
    public static function now(){
        return static::getAllDays()[now()->format('D')];
    }

    public static function tomorrow(){
        return static::getAllDays()[now()->addDay()->format('D')];
    }

    private static function getAllDays() : array {
        // Get All Days and equal days Date , Day DB
        $selLang = Language::where('code','ar')->first();
        $daysDB = DayTranslation::where('language_id',$selLang->id)->get();
        $days = [
            "Sun" => $daysDB->where('name','الاحد')->first()->id,
            "Mon" => $daysDB->where('name','الاثنين')->first()->id,
            "Tue" => $daysDB->where('name','الثلاثاء')->first()->id,
            "Wed" => $daysDB->where('name','الاربعاء')->first()->id,
            "Thu" => $daysDB->where('name','الخميس')->first()->id,
            "Fri" => $daysDB->where('name','الجمعة')->first()->id,
            "Sat" => $daysDB->where('name','السبت')->first()->id
        ];
        return $days;
    }
}
