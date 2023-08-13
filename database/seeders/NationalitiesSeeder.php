<?php

namespace Database\Seeders;

use App\Models\Nationalitie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nationalities')->delete();

        $nationals = [


            'أفغانستاني', 'جزائري', 'أرجنتيني', 'بحريني', 'بنغلاديشي', 'روسي', 'بلجيكي', 'مصري',   'هندي', 'عراقي',
            'إيرلندي',    'إيطالي', 'أردني', 'كويتي', 'ليبي', 'مغربي', 'باكستاني', 'بالاوي', 'فلسطيني', 'فلبيني', 'بيتكيرني', 'بوليني', 'برتغالي', 'قطري', 'روماني', 'روسي', 'سنغافوري', 'صومالي', 'سوداني', 'سويدي', 'سويسري', 'سوري', 'تايواني', 'طاجيكستاني', 'تايلندي', 'تونسي', 'تركي', 'أوكراني', 'إماراتي', 'بريطاني', 'أمريكي', 'أورغواي', 'أوزباكستاني', 'فنزويلي', 'يمني'
        ];

        foreach ($nationals as $n) {
            Nationalitie::create(['Name' => $n]);
        }
    }
}