<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(NationalitiesSeeder::class);

        $image_file_path = public_path('images/avatar.jpg');
        $image_data = file_get_contents($image_file_path);
        $base64_image = base64_encode($image_data);

        \App\Models\User::factory()->create([
            "name" => "Super Admin",
            "email" => "super_admin@admin.com",
            "password" => bcrypt('0123456789'),
            "departement" => "Admin System",
            "image" => $base64_image,
            "gender" => "male",
            "type" => 1,
            "status" => 1,
            "company_id" => null,
            "phone" => "0969040382",
            "serial_number" => "000000",
            "nationalitie_id" => 32
        ]);
    }
}
