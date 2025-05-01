<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $now = Carbon::now();

        $methods = [
            [
                'name' => 'cash',
                'config' => ['fee' => rand(1, 5)],
            ],
            [
                'name' => 'online',
                'config' => ['fee' => rand(1, 5)],
            ],
            [
                'name' => 'crypto',
                'config' => ['fee' => rand(1, 5)],
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $method['name']],
                [
                    'config' => json_encode($method['config']),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
