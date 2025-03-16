<?php

namespace Database\Seeders;

use App\Models\Localization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     */

    public function run(): void
    {
        //

        $engFilePath = base_path('resources/js/assets/en-US.json');
        $engData = file_get_contents($engFilePath);
        $engArraydata = json_decode($engData, true);

        $myFilePath = base_path('resources/js/assets/my-MM.json');
        $myData = file_get_contents($myFilePath);
        $myArraydata = json_decode($myData, true);

        foreach ($engArraydata as $key => $value) {
            Localization::create([
                'key' => $key,
                'value' => $value,
                'language_id' => 1,
                'created_at' => now()
            ]);
        }
        foreach ($myArraydata as $key => $value) {
            Localization::create([
                'key' => $key,
                'value' => $value,
                'language_id' => 2,
                'created_at' => now()
            ]);
        }
    }
}
