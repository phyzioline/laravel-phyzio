<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-super 
                            {--email=admin@phyzioline.com : Email address for the super admin}
                            {--name=Super Admin : Name of the super admin}
                            {--password= : Password for the super admin (will be prompted if not provided)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user with all permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $password = $this->option('password');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            if (!$this->confirm("User with email {$email} already exists. Do you want to update it?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        // Prompt for password if not provided
        if (!$password) {
            $password = $this->secret('Enter password for super admin');
            $passwordConfirmation = $this->secret('Confirm password');
            
            if ($password !== $passwordConfirmation) {
                $this->error('Passwords do not match!');
                return 1;
            }
        }

        // Get or create super-admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );

        // Sync all permissions to super-admin role
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Create or update user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'phone' => '123456789',
                'status' => 'active'
            ]
        );

        // Assign super-admin role
        $user->assignRole($superAdminRole);

        $this->info("Super admin user created successfully!");
        $this->line("Email: {$email}");
        $this->line("Name: {$name}");
        $this->line("Role: super-admin");
        $this->line("Permissions: All permissions assigned");

        return 0;
    }
}

