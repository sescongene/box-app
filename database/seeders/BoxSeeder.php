<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Box;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boxes = [
            ["name" => "BOXA", "length" => 20, "width" => 15, "height" => 10, "weight_limit" => 5],
            ["name" => "BOXB", "length" => 30, "width" => 25, "height" => 20, "weight_limit" => 10],
            ["name" => "BOXC", "length" => 60, "width" => 55, "height" => 50, "weight_limit" => 50],
            ["name" => "BOXD", "length" => 50, "width" => 45, "height" => 40, "weight_limit" => 30],
            ["name" => "BOXE", "length" => 40, "width" => 35, "height" => 30, "weight_limit" => 20],
        ];

        foreach ($boxes as $box) {
            Box::create($box);
        }
    }
}
