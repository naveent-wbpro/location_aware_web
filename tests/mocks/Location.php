<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/10/17
 * Time: 2:50 PM
 */

namespace Mock;


class Location
{
    /**
     * @param $user_id
     * @param array $data
     * @return \App\Location
     */
    public static function create($user_id, $data = [])
    {
        $location = new \App\Location();
        $location->user_id = $user_id;
        $location->latitude = isset($data['latitude']) ? $data['latitude'] : '32.3405131';
        $location->longitude = isset($data['longitude']) ? $data['longitude'] : '-90.0825253';
        if (isset($data['created_at'])) {
            $location->created_at = $data['created_at'];
        }
        $location->save();

        return $location;
    }
}
