<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Pending', 'Ongoing', 'Done'];
        foreach ($statuses as $status) {
            $existingStatus = Status::where('name', $status)->first();
            if (!$existingStatus) {
                Status::create([
                    'name' => $status,
                ]);
            }
        }
    }
}