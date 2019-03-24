<?php

namespace App\Libraries;

class Coordinates
{
    public $locationsArray = [];

    public function getAvailableOffices()
    {
        $contractors = Contractor::where('iicrc_certified', '=', 1)->lists('id');
        $addresses = Address::whereIn('contractor_id', $contractors)->with('contractor')->get();
        foreach ($addresses as $address) {
            if (!empty($address->contractor->name) && $address->contractor->iicrc_certified == 1) {
                $this->locationsArray += [
                    $address->id => [
                        'type' => 'Office',
                        'contractor_name' => $address->contractor->name,
                        'contractor_id' => $address->contractor->id,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'icon' => '/images/homegardenbusiness.png'
                    ]
                ];
            }
        };
    }

    public function getAvailableTrucks()
    {
        $app_tokens = AppToken::with('location', 'user.contractors', 'user.employers')->where('active', '=', 1)->get();

        if (count($app_tokens) > 0) {
            foreach ($app_tokens as $app) {
                //If app is employee
                if (!empty($app->location) && !empty($app->user->employers[0]) && $app->user->employers[0]->iicrc_certified == 1) {
                    $this->locationsArray += [
                        'emp_'.$app->user->id => [
                            'type' => 'Mobile Unit',
                            'contractor_name' => $app->user->employers[0]->name,
                            'contractor_id' => $app->user->employers[0]->id,
                            'latitude' => $app->location->latitude,
                            'longitude' => $app->location->longitude,
                            'icon' => '/images/truck.png'
                        ]
                    ];
                }
                //If app is contractor
                if (!empty($app->location) && !empty($app->user->contractors[0]) && $app->user->contractors[0]->iicrc_certified == 1) {
                    $this->locationsArray += [
                        'contractor_'.$app->user->id => [
                            'type' => 'Mobile Unit',
                            'contractor_name' => $app->user->contractors[0]->name,
                            'contractor_id' => $app->user->contractors[0]->id,
                            'latitude' => $app->location->latitude,
                            'longitude' => $app->location->longitude,
                            'icon' => '/images/truck.png'
                        ]
                    ];
                }
            }
        }
    }

    public function getLocationsArray()
    {
        return json_encode($this->locationsArray);
    }

    public static function streetAddress($address)
    {
        $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address . "&key=AIzaSyA0mCfi0joCP1dDeZ30spA3Eb8zGofMkwU";
        $response = file_get_contents($url);
        $json = json_decode($response, true);

        if (isset($json['results'][0])) {
            $lat = $json['results'][0]['geometry']['location']['lat'];
            $lng = $json['results'][0]['geometry']['location']['lng'];

            return json_decode(json_encode(["latitude" => $lat, "longitude" => $lng]), false);
        } else {
            return json_encode([]);
        }
    }
}
