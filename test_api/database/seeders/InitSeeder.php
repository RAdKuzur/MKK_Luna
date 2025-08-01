<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyPhone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        CompanyActivity::truncate();
        CompanyPhone::truncate();
        Company::truncate();
        Activity::truncate();
        Building::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $buildings = [
            ['address' => 'г. Москва, ул. Ленина 1, офис 3', 'latitude' => 55.7558, 'longitude' => 37.6173],
            ['address' => 'г. Санкт-Петербург, Невский проспект 25', 'latitude' => 59.9311, 'longitude' => 30.3609],
            ['address' => 'г. Екатеринбург, ул. Блюхера 32/1', 'latitude' => 56.8519, 'longitude' => 60.6122],
            ['address' => 'г. Казань, пр-т Победы 15', 'latitude' => 55.7961, 'longitude' => 49.1064],
            ['address' => 'г. Новосибирск, Красный проспект 45', 'latitude' => 55.0084, 'longitude' => 82.9357],
            ['address' => 'г. Нижний Новгород, ул. Горького 10', 'latitude' => 56.3269, 'longitude' => 44.0059],
            ['address' => 'г. Самара, Московское шоссе 50', 'latitude' => 53.1959, 'longitude' => 50.1008],
            ['address' => 'г. Челябинск, пр-т Ленина 100', 'latitude' => 55.1644, 'longitude' => 61.4368],
            ['address' => 'г. Омск, ул. Маркса 7', 'latitude' => 54.9885, 'longitude' => 73.3242],
            ['address' => 'г. Краснодар, ул. Северная 8', 'latitude' => 45.0355, 'longitude' => 38.9753],
        ];
        foreach ($buildings as $data) {
            Building::create($data);
        }

        $activities = [
            'Еда' => ['Мясная продукция', 'Молочная продукция', 'Хлебобулочные изделия', 'Овощи и фрукты', 'Напитки'],
            'Автомобили' => ['Грузовые', 'Легковые' => ['Запчасти', 'Аксессуары'], 'Электромобили'],
            'Строительство' => ['Материалы', 'Инструменты', 'Отделочные работы'],
            'IT' => ['Разработка ПО', 'Хостинг', 'Сетевое оборудование'],
        ];

        $activityMap = [];

        foreach ($activities as $parent => $children) {
            $parentActivity = Activity::create(['name' => $parent, 'parent_id' => null]);
            foreach ($children as $child => $sub) {
                if (is_array($sub)) {
                    $childActivity = Activity::create(['name' => $child, 'parent_id' => $parentActivity->id]);
                    foreach ($sub as $subChild) {
                        $activityMap[] = Activity::create(['name' => $subChild, 'parent_id' => $childActivity->id]);
                    }
                } else {
                    $activityMap[] = Activity::create(['name' => $sub, 'parent_id' => $parentActivity->id]);
                }
            }
        }


        $companies = [
            ['name' => 'ООО "Рога и Копыта"', 'building_id' => 1],
            ['name' => 'ЗАО "АвтоСнаб"', 'building_id' => 2],
            ['name' => 'ИП Иванов', 'building_id' => 3],
            ['name' => 'ООО "ТехСтрой"', 'building_id' => 4],
            ['name' => 'ООО "Вкусный дом"', 'building_id' => 1],
            ['name' => 'ЗАО "Хлебный рай"', 'building_id' => 5],
            ['name' => 'ИП Петров', 'building_id' => 2],
            ['name' => 'ООО "ЭлектроАвто"', 'building_id' => 6],
            ['name' => 'ЗАО "СофтСервис"', 'building_id' => 7],
            ['name' => 'ИП Сидоров', 'building_id' => 8],
            ['name' => 'ООО "ФруктыМаркет"', 'building_id' => 9],
            ['name' => 'ЗАО "Напитки плюс"', 'building_id' => 10],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            for ($i = 0; $i < rand(1, 3); $i++) {
                CompanyPhone::create([
                    'company_id' => $company->id,
                    'phone_number' => sprintf("8-9%d%d-%d%d-%d%d", rand(1, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9))
                ]);
            }

            $randomActivities = collect($activityMap)->random(rand(1, 3));
            foreach ($randomActivities as $act) {
                CompanyActivity::create([
                    'company_id' => $company->id,
                    'activity_id' => $act->id
                ]);
            }
        }
    }
}
