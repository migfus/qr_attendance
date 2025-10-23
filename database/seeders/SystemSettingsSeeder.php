<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{SystemSettings, SystemSettingCategory, SystemSettingType};

class SystemSettingsSeeder extends Seeder
{
    public function run(): void {
        $category_general = SystemSettingCategory::create([
                'name' => 'General',
                'icon' =>
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    ',
                'sort_id' => 1,
                'description' => 'The system\'s representation settings',
                'href' => 'general'
            ]);

            $type_text = SystemSettingType::create([
                'name' => 'text'
            ]);
            $type_avatar = SystemSettingType::create([
                'name' => 'avatar'
            ]);

            $data = [
            [
                'name' => 'System Name (Title)', // NOTE hard coded to HandleInertiaRequests, [Remember to change if you do]
                'sort_id' => 1,
                'system_setting_type_id' => $type_text->id,
                'system_setting_category_id' => $category_general->id,
                'description' => 'Branding Name for the System',
                'value' => 'Office of Human Resources Management'
            ],
            [
                'name' => 'System Logo', // NOTE hard coded to HandleInertiaRequests, [Remember to change if you do]
                'sort_id' => 2,
                'system_setting_type_id' => $type_avatar->id,
                'system_setting_category_id' => $category_general->id,
                'description' => 'Branding Name for the System',
                'value' => '/assets/logo.png'
            ],
            [
                'name' => 'System Small Logo (for page title)', // NOTE hard coded to HandleInertiaRequests, [Remember to change if you do]
                'sort_id' => 3,
                'system_setting_type_id' => $type_avatar->id,
                'system_setting_category_id' => $category_general->id,
                'description' => 'Branding Name for the System',
                'value' => '/assets/logo-sm.png'
            ],
        ];

        foreach($data as $row) {
            SystemSettings::create($row);
        }
    }
}
