<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public function get($id){
        return Company::find($id);
    }
    public function getAll(){
        return Company::all();
    }
    public function getByBuildingId($buildingId)
    {
        return Company::where(['building_id' => $buildingId])->get();
    }
    public function getByBuildingIds($buildingId)
    {
        return Company::whereIn('building_id', $buildingId)->get();
    }
    public function getByName($name)
    {
        return Company::where(['name' => $name])->first();
    }
    public function save(Company $company){
        return $company->save();
    }
    public function delete(Company $company){
        return $company->delete();
    }
}
