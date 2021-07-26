<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAuthSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {  
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        $password = 123456;
        $role_super_admin = Role::create(['name' => 'super-admin']); // gets all permissions via Gate::before rule; see AuthServiceProvider        
        $role_admin = Role::create(['name' => 'bank-user']);
     

        $permissions_admin = ['account-manager'];
        $permissions_web = []; // ['product-editor'];

        foreach ($permissions_admin as $permission) {
            Permission::create(['name' => $permission])->syncRoles([$role_admin]);
            // Permission::create(['name' => $permission])->syncRoles([$role_1, $role_2]);
        }     
        
        foreach ($permissions_web as $permission) {
           // Permission::create(['name' => $permission])->syncRoles([$role_web]);
        }          

        // create demo users

        $user = \App\Models\User::factory()->create([
            'lastname' => 'Hernández',
        	'name' => 'Sandro G.', 
            'document' => 000001,
        	'email' => 'shernandez@example.com',
        	'password' => bcrypt($password),
        ]);
        $user->assignRole($role_super_admin);        

        $user = \App\Models\User::factory()->create([
            'lastname' => 'Villegas',
            'name' => 'Laura',
            'document' => 000002,
            'email' => 'laura@example.com',
            'password' => bcrypt($password),
        ]);
        $user->assignRole($role_admin);

        $user = \App\Models\User::factory()->create([
            'lastname' => 'Paez Perea',
            'name' => 'Pedro',
            'document' => 000003,
            'email' => 'pedro@example.com',
            'password' => bcrypt($password),
        ]);
        $user->assignRole($role_admin);

    }
}
