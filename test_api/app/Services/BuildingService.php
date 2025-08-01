<?php

namespace App\Services;

use App\Models\Building;
use App\Repositories\BuildingRepository;

class BuildingService
{
    public const CIRCLE = 1;
    public const SQUARE = 2;
    private BuildingRepository $buildingRepository;
    public function __construct(BuildingRepository $buildingRepository){
        $this->buildingRepository = $buildingRepository;
    }
    public function locationBuilding($type, $radius, $longitude, $latitude)
    {

        if($type == self::CIRCLE){
            return $this->buildingRepository->squareLocation($radius, $longitude, $latitude);
        }
        else {
            return $this->buildingRepository->circleLocation($radius, $longitude, $latitude);
        }
    }
}
