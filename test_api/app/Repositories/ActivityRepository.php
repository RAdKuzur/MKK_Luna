<?php

namespace App\Repositories;

use App\Models\Activity;

class ActivityRepository
{
    public function get($id)
    {
        return Activity::find($id);
    }
    public function getAll(){
        return Activity::all();
    }
    public function getByIds($ids)
    {
        return Activity::whereIn('id', $ids)->get();
    }
    public function getByParentId($id){
        return Activity::where(['parent_id' => $id])->get();
    }
}
