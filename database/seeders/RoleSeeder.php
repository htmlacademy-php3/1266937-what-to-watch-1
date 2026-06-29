<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\RoleName;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleName::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }
    }
}
