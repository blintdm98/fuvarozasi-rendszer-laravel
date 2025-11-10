<?php

namespace Database\Seeders;

use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\Job;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedCarriersWithVehiclesAndJobs();
        $this->seedUnassignedJob();
    }

    protected function seedAdminUser(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('123qwe123'),
                'role' => UserRole::Admin,
            ]
        );
    }

    protected function seedCarriersWithVehiclesAndJobs(): void
    {
        $carriers = [
            [
                'name' => 'Kovács Péter',
                'email' => 'peter.kovacs@example.com',
                'vehicle' => [
                    'brand' => 'Mercedes',
                    'model' => 'Sprinter',
                    'license_plate' => 'ABC-123',
                ],
                'jobs' => [
                    [
                        'pickup_address' => 'Budapest, Fő utca 1.',
                        'delivery_address' => 'Győr, Kossuth tér 5.',
                        'recipient_name' => 'Szabó Anna',
                        'recipient_phone' => '+36 30 111 2233',
                        'status' => JobStatus::InProgress,
                    ],
                    [
                        'pickup_address' => 'Debrecen, Piac utca 10.',
                        'delivery_address' => 'Miskolc, Szent István út 2.',
                        'recipient_name' => 'Kiss Gábor',
                        'recipient_phone' => '+36 20 444 5566',
                        'status' => JobStatus::Assigned,
                    ],
                ],
            ],
            [
                'name' => 'Nagy Eszter',
                'email' => 'eszter.nagy@example.com',
                'vehicle' => [
                    'brand' => 'Ford',
                    'model' => 'Transit',
                    'license_plate' => 'XYZ-789',
                ],
                'jobs' => [
                    [
                        'pickup_address' => 'Szeged, Tisza Lajos körút 12.',
                        'delivery_address' => 'Pécs, Király utca 18.',
                        'recipient_name' => 'Horváth Lajos',
                        'recipient_phone' => '+36 70 777 8899',
                        'status' => JobStatus::Completed,
                        'completed_at' => now()->subDay(),
                    ],
                    [
                        'pickup_address' => 'Budapest, Váci út 45.',
                        'delivery_address' => 'Székesfehérvár, Fő tér 3.',
                        'recipient_name' => 'Tóth Márta',
                        'recipient_phone' => '+36 30 999 0011',
                        'status' => JobStatus::Failed,
                        'completed_at' => now()->subHours(12),
                    ],
                ],
            ],
        ];

        foreach ($carriers as $carrierData) {
            $carrier = User::updateOrCreate(
                ['email' => $carrierData['email']],
                [
                    'name' => $carrierData['name'],
                    'password' => Hash::make('123qwe123'),
                    'role' => UserRole::Carrier,
                ]
            );

            $carrier->vehicle()->updateOrCreate(
                [],
                $carrierData['vehicle']
            );

            foreach ($carrierData['jobs'] as $jobData) {
                $carrier->jobs()->create([
                    'pickup_address' => $jobData['pickup_address'],
                    'delivery_address' => $jobData['delivery_address'],
                    'recipient_name' => $jobData['recipient_name'],
                    'recipient_phone' => $jobData['recipient_phone'],
                    'status' => $jobData['status'],
                    'assigned_at' => now()->subDays(rand(1, 3)),
                    'completed_at' => $jobData['completed_at'] ?? null,
                ]);
            }
        }
    }

    protected function seedUnassignedJob(): void
    {
        Job::create([
            'pickup_address' => 'Budapest, Andrássy út 60.',
            'delivery_address' => 'Tatabánya, Béla király út 7.',
            'recipient_name' => 'Fekete Dóra',
            'recipient_phone' => '+36 30 222 3344',
            'status' => JobStatus::Assigned,
            'assigned_at' => null,
        ]);
    }
}
