<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;

/**
 * Class Location
 * @package App
 *
 * @property int $id
 * @property int $user_id
 * @property float $latitude
 * @property float $longitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Location extends Model
{
    /**
     *
     */
    public static function boot()
    {
        Location::saved(function ($model) {
            /** @var \App\Location $model */
            $model->checkRequestStatus();
        });
    }

    public function user()
    {
        return $this->belongsTo('\App\User')->with('assignments');
    }

    /**
     *
     */
    public function checkRequestStatus()
    {
        if (!empty($this->user)) {
            $assignments = $this->user->activeAssignments;
            $current_location = GeoLocation::fromDegrees($this->latitude, $this->longitude);

            /** @var \App\Request $assignment */
            foreach ($assignments as $assignment) {
                $assignment_location = GeoLocation::fromDegrees($assignment->latitude, $assignment->longitude);
                $distance_to_assignment = $assignment_location->distanceTo($current_location, 'miles');

                if ($assignment->arrived_on_user_id == $this->user_id && $distance_to_assignment > 0.5 && $assignment->departed_on == null) {
                    $assignment->departed_on = \Carbon\Carbon::now();
                    $assignment->save();
                }

                if ($assignment->arrived_on == null && $distance_to_assignment < 0.5) {
                    $assignment->arrived_on =  \Carbon\Carbon::now();
                    $assignment->arrived_on_user_id = $this->user_id;
                    $assignment->save();
                }
            }
        }
    }
}
