<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{Feedback, FeedbackType};

class FeedbackSeeder extends Seeder
{
    public function run(): void {
        Feedback::create([
            'feedback_type_id' => FeedbackType::where('name', 'Issues')->first()->id,
            'title' => '[Title]',
            'content' => 'Content'
        ]);
    }
}
