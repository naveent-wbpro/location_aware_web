<?php

namespace App\Libraries;

class Timezone
{
    public static function america()
    {
        $array = [
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Phoenix',
            'America/Los_Angeles',
            'America/Anchorage',
            'America/Adak',
            'Pacific/Honolulu'
        ];

        return array_combine($array, $array);
    }
}
