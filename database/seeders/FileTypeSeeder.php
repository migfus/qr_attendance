<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FileType;

class FileTypeSeeder extends Seeder {

    public function run(): void {
        $data = [
            ['name' => 'Uploaded By Staff'],
            ['name' => 'Saved from Editor'],
            ['name' => 'Cropped'],
            ['name' => 'Raw'],
        ];

        foreach ($data as $row) {
            FileType::create($row);
        }
    }
}
