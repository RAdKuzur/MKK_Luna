<?php

namespace App\Repositories;

use App\Models\CompanyActivity;

class CompanyActivityRepository
{
    public function get($id){
        return CompanyActivity::find($id);
    }
    public function getAll(){
        return CompanyActivity::all();
    }
    public function getByCompanyId($id)
    {
        return CompanyActivity::where(['company_id' => $id])->get();
    }
    public function getByActivityId($id)
    {
        return CompanyActivity::where(['activity_id' => $id])->get();
    }
    public function getByActivityIds($ids)
    {
        return CompanyActivity::whereIn('activity_id' , $ids)->get();
    }
    public function save(CompanyActivity $companyActivity){
        return $companyActivity->save();
    }
    public function delete(CompanyActivity $companyActivity){
        return $companyActivity->delete();
    }
}
