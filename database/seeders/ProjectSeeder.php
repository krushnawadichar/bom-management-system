<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'project_code' => 'P23-001',
            'project_name' => 'Duplex Heat Exchanger Project',
            'customer_name' => 'Voith',
            'work_order_no' => '1237120199',
            'status' => 'active',
            'created_by' => 1
        ]);
        
        Project::create([
            'project_code' => 'P23-002',
            'project_name' => 'Pressure Vessel Manufacturing',
            'customer_name' => 'Reliance Industries',
            'work_order_no' => 'RL2024001',
            'status' => 'active',
            'created_by' => 1
        ]);
        
        Project::create([
            'project_code' => 'P23-003',
            'project_name' => 'Heat Exchanger Bundle',
            'customer_name' => 'Shell',
            'work_order_no' => 'SHELL2024001',
            'status' => 'draft',
            'created_by' => 1
        ]);
    }
}