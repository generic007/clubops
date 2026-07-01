<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Enums\AgentRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Agent::create([
            'name' => 'Club Owner',
            'email' => 'owner@clubops.local',
            'password' => Hash::make('change-me'),
            'role' => AgentRole::Owner,
            'active' => true,
        ]);

        Agent::create([
            'name' => 'Club Manager',
            'email' => 'manager@clubops.local',
            'password' => Hash::make('change-me'),
            'role' => AgentRole::Manager,
            'active' => true,
        ]);

        $this->command->info('Admin accounts created.');
        $this->command->warn('Change passwords immediately after first login.');
    }
}
