<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{QuickResponse, User};

class QuickResponseSeeder extends Seeder
{
    public function run(): void {
        $qr = [
            [
                'id' => 'qr-code-01-for-admin',
                'user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            ],
            [
                'id' => 'qr-code-02-for-manager',
                'user_id' => User::where('email', 'manager@email.com')->first()->id,
            ],
            [
                'id' => 'qr-code-03-for-scanner',
                'user_id' => User::where('email', 'scanner@email.com')->first()->id,
            ],
            [
                'id' => 'qr-code-04-for-member',
                'user_id' => User::where('email', 'member@email.com')->first()->id,
            ],
        ];

        foreach($qr as $row) {
            QuickResponse::create($row);
        }
    }
}
