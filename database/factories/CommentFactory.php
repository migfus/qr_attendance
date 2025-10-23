<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Post, User};

class CommentFactory extends Factory
{
  public function definition(): array
  {
    return [
      'post_id' => Post::where('content', '[Content Here]')->first()->id,
      'user_id' => User::where('name', '[Admin User]')->first()->id,
      'content' =>$this->faker->sentence(45)
    ];
  }
}
