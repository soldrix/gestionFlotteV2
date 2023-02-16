<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'RH']);
        Role::create(['name' => 'responsable auto']);
        Role::create(['name' => 'fournisseur']);
        Role::create(['name' => 'responsable fournisseur']);
        Role::create(['name' => 'responsable agences']);
        Role::create(['name' => 'chef agence']);
        Role::create(['name' => 'secretaire']);
        $role2 = Role::create(['name' => 'admin']);
        $user2 = \App\Models\User::factory()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'id_role' => 2
        ]);
        $user2->assignRole($role2);
    }
}
