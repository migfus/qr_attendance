<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Group, User};

class PostFactory extends Factory {
    public function definition(): array {
        static $order = 1;
        return [
            'user_id' => User::where('name', '[Admin User]')->first()->id,
            'group_id' => Group::where('name', 'Office of Human Resources Management Office (OHRM)')->first()->id,
            'content' => '[{"insert":"' . $order++ . '"}]',
            'is_pinned' => false,
        ];
    }
}
