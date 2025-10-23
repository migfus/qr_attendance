<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'mid_name' => $this->faker->suffix,
            'extra_name_id' => 1,
            'email' => $this->faker->email,
            'department_id' => $req->department_id,
            'position_id' => $req->position_id,
            'employee_status_id' => $req->status_id,
            'claim_type_id' => $req->claim_id,
            'password' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) // generate pin (XXXX)
        ];
    }
}
