<?php

namespace App\Repositories;

use App\Models\Building;

class BuildingRepository
{
    public function get($id)
    {
        return Building::find();
    }
    public function getAll(){
        return Building::all();
    }
    public function squareLocation($radius, $longitude, $latitude){
        $minLongitude = $longitude - $radius;
        $maxLongitude = $longitude + $radius;
        $minLatitude = $latitude - $radius;
        $maxLatitude = $latitude + $radius;
        return Building::whereBetween('longitude', [$minLongitude, $maxLongitude])
            ->whereBetween('latitude', [$minLatitude, $maxLatitude])
            ->get();
    }
    public function circleLocation($radius, $longitude, $latitude){
        return Building::whereRaw(
            '(POWER(longitude - ?, 2) + POWER(latitude - ?, 2)) <= POWER(?, 2)',
            [$longitude, $latitude, $radius]
        )->get();
    }
}
