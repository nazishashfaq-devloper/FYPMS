<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domain;

class DomainSeeder extends Seeder
{
    public function run()
    {
        $domains = [
            ['name' => 'Web Development', 'description' => 'Web-based applications and platforms.'],
            ['name' => 'Artificial Intelligence', 'description' => 'AI, machine learning, and intelligent systems.'],
            ['name' => 'Data Science', 'description' => 'Data analysis, visualization, and analytics projects.'],
            ['name' => 'Networking', 'description' => 'Computer networks, security, and infrastructure.'],
            ['name' => 'Mobile Applications', 'description' => 'Android, iOS, and cross-platform mobile apps.'],
        ];

        foreach ($domains as $domain) {
            Domain::updateOrCreate(['name' => $domain['name']], $domain);
        }
    }
}
