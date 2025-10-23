<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{User, Role, UserSettings, Department};

class UserSeeder extends Seeder {
    private function configDefaultValue() {
        return json_encode([
            'themes' => 'Light',
            'card' => [
                'show_description' => false
            ],
            'notification' => [
                'push' => [
                    'registered' => true,
                ],
                'email' => [
                    'registered' => false,
                ],
                'sms' => [
                    'registered' => true,
                ],
            ],
            'request_status_messages' => [
                'Unverified' => 'Your request has been unverified and pending.',
                'Processing' => 'Your request approved and currently processing.',
                'Completed' => 'Your request is completed, you can claim the Arta ID now.',
                'Claimed' => 'Your Arta ID is now claimed.',
                'Rejected' => 'Unfortunately your request was rejected due to incorrect info.',
                'Removed' => 'Your request is now removed. It will be deleted in 30 days.',
            ],
        ]);
    }

    public function run(): void {
        // NOTE: SYSTEM ROLE
        $roles = [
            'admin' => Role::where('name', 'admin')->first(), // ignore for now
            'arta-manager' => Role::where('name', 'arta-manager')->first(), // manager
            'inactive' => Role::where('name', 'inactive')->first(), // inactive
        ];
        $departments = [
            'ohrm' => Department::where('short_name', 'OHRM')->first()->id,
        ];

        $user = User::create([
            'name' => '[Admin User]',
            'email' => 'migfus20@gmail.com',
            'avatar_url' => 'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80',
            'password' => bcrypt('admin.123'),
            'department_id' => $departments['ohrm'],
        ])
            ->addRole($roles['admin']);
        UserSettings::create(['user_id' => $user->id, 'config' => $this->configDefaultValue()]);

        $user = User::create([
            'name' => '[Manager User]',
            'email' => 'arta-manager-test@gmail.com',
            'avatar_url' => 'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80',
            'password' => bcrypt('arta-manager-test@gmail.com123'),
            'department_id' => $departments['ohrm'],

        ])
            ->addRole($roles['arta-manager']);
        UserSettings::create([
            'user_id' => $user->id,
            'config' => $this->configDefaultValue()
        ]);

        // $user = User::create([
        //     'name' => '[Scanner User]',
        //     'email' => 'scanner@email.com',
        //     'avatar_url' => 'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80',
        //     'password' => bcrypt('scanner.123'),
        //     'department_id' => Department::where('name', 'OHRM - Office of Human Resesource Management')->first()->id,

        // ])
        //     ->addRole($roles['scanner']);
        // UserSettings::create([
        //     'user_id' => $user->id,
        //     'config' => $this->configDefaultValue()
        // ]);

        // $user = User::create([
        //     'name' => '[Member User]',
        //     'email' => 'member@email.com',
        //     'avatar_url' => 'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80',
        //     'password' => bcrypt('member.123'),
        //     'department_id' => Department::where('name', 'OHRM - Office of Human Resesource Management')->first()->id,

        // ])
        //     ->addRole($roles['member']);
        // UserSettings::create([
        //     'user_id' => $user->id,
        //     'config' => $this->configDefaultValue()
        // ]);
    }
}
