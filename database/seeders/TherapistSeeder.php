<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TherapistProfile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure therapist role exists
        $role = Role::firstOrCreate(['name' => 'therapist', 'guard_name' => 'web']);

        $therapists = [
            [
                'name' => 'Dr. Ahmed Hassan',
                'email' => 'ahmed@phyzioline.com',
                'specialization' => 'Sports Injury',
                'bio' => 'Senior physiotherapist with 10 years of experience in sports injuries and rehabilitation.',
                'hourly_rate' => 500,
                'home_visit_rate' => 700,
                'rating' => 4.8,
                'image' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=500',
                'available_areas' => ['Nasr City', 'New Cairo', 'Heliopolis'],
                'years_experience' => 10
            ],
            [
                'name' => 'Dr. Sarah Mohamed',
                'email' => 'sarah@phyzioline.com',
                'specialization' => 'Pediatric Therapy',
                'bio' => 'Specialized in pediatric physical therapy and developmental delays.',
                'hourly_rate' => 450,
                'home_visit_rate' => 600,
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=500',
                'available_areas' => ['Maadi', 'Downtown', 'Zamalek'],
                'years_experience' => 8
            ],
            [
                'name' => 'Dr. Mahmoud Ali',
                'email' => 'mahmoud@phyzioline.com',
                'specialization' => 'Orthopedic',
                'bio' => 'Expert in orthopedic rehabilitation and post-surgery recovery.',
                'hourly_rate' => 400,
                'home_visit_rate' => 550,
                'rating' => 4.7,
                'image' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=500',
                'available_areas' => ['Giza', 'Dokki', 'Mohandessin'],
                'years_experience' => 6
            ],
            [
                'name' => 'Dr. Nour El-Din',
                'email' => 'nour@phyzioline.com',
                'specialization' => 'Neurological',
                'bio' => 'Specialized in neurological conditions including stroke and spinal cord injuries.',
                'hourly_rate' => 600,
                'home_visit_rate' => 800,
                'rating' => 5.0,
                'image' => 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?w=500',
                'available_areas' => ['Sheikh Zayed', '6th of October'],
                'years_experience' => 12
            ],
            [
                'name' => 'Dr. Kareem Adel',
                'email' => 'kareem@phyzioline.com',
                'specialization' => 'Geriatric',
                'bio' => 'Focusing on physical therapy for elderly patients to improve mobility and quality of life.',
                'hourly_rate' => 400,
                'home_visit_rate' => 500,
                'rating' => 4.6,
                'image' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?w=500',
                'available_areas' => ['Nasr City', 'Heliopolis'],
                'years_experience' => 5
            ]
        ];

        foreach ($therapists as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'phone' => '01000000000',
                    'status' => 'active',
                    'image' => $data['image']
                ]
            );

            $user->assignRole($role);

            TherapistProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $data['specialization'],
                    'bio' => $data['bio'],
                    'hourly_rate' => $data['hourly_rate'],
                    'home_visit_rate' => $data['home_visit_rate'],
                    'rating' => $data['rating'],
                    'years_experience' => $data['years_experience'],
                    'available_areas' => $data['available_areas'],
                    'status' => 'approved',
                    'total_reviews' => rand(10, 50)
                ]
            );
        }
    }
}
