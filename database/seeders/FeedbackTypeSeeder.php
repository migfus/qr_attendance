<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FeedbackType;

class FeedbackTypeSeeder extends Seeder
{
    public function run(): void {
        $data = [
            ['name' => 'Issues'],
            ['name' => 'Improvements'],
        ];

        foreach($data as $row) {
            FeedbackType::create($row);
        }
    }
}
