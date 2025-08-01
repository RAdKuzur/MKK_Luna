<?php

namespace App\Services;

use App\Repositories\ActivityRepository;

class ActivityService
{
    private ActivityRepository $activityRepository;
    public function __construct(
        ActivityRepository $activityRepository
    )
    {
        $this->activityRepository = $activityRepository;
    }

    public function getActivityTree($id,  &$data = []){
        $activities = $this->activityRepository->getByParentId($id);
        foreach ($activities as $activity){
            $this->getActivityTree($activity->id, $data);
            $data[] = $activity->id;
        }
        return $data;
    }
}
