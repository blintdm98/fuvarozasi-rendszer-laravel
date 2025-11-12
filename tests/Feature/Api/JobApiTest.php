<?php

namespace Tests\Feature\Api;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\JobAlert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_can_be_created_via_api(): void
    {
        $carrier = User::factory()->create();

        $payload = [
            'pickup_address' => 'Budapest, Fő utca 1.',
            'delivery_address' => 'Győr, Kossuth tér 5.',
            'recipient_name' => 'Teszt Elek',
            'recipient_phone' => '+36 30 111 2233',
            'status' => JobStatus::Assigned->value,
            'carrier_id' => $carrier->id,
        ];

        $response = $this->postJson('/api/jobs', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.pickup_address', $payload['pickup_address'])
            ->assertJsonPath('data.status', JobStatus::Assigned->value)
            ->assertJsonPath('data.carrier.id', $carrier->id);

        $this->assertDatabaseHas('transport_jobs', [
            'pickup_address' => $payload['pickup_address'],
            'delivery_address' => $payload['delivery_address'],
            'status' => JobStatus::Assigned->value,
            'carrier_id' => $carrier->id,
        ]);
    }

    public function test_job_can_be_updated_via_api(): void
    {
        $carrier = User::factory()->create();

        $job = Job::create([
            'pickup_address' => 'Budapest, Akácfa utca 4.',
            'delivery_address' => 'Pécs, Király utca 12.',
            'recipient_name' => 'Kiss Kata',
            'recipient_phone' => '+36 30 222 3344',
            'status' => JobStatus::Assigned,
            'carrier_id' => $carrier->id,
        ]);

        $payload = [
            'delivery_address' => 'Pécs, Ferencesek utcája 9.',
            'recipient_phone' => '+36 30 555 6677',
            'status' => JobStatus::InProgress->value,
        ];

        $response = $this->putJson("/api/jobs/{$job->id}", $payload);

        $response
            ->assertOk()
            ->assertJsonPath('data.delivery_address', $payload['delivery_address'])
            ->assertJsonPath('data.status', JobStatus::InProgress->value);

        $this->assertDatabaseHas('transport_jobs', [
            'id' => $job->id,
            'delivery_address' => $payload['delivery_address'],
            'recipient_phone' => $payload['recipient_phone'],
            'status' => JobStatus::InProgress->value,
        ]);
    }

    public function test_job_status_update_creates_failure_alert(): void
    {
        $carrier = User::factory()->create();

        $job = Job::create([
            'pickup_address' => 'Debrecen, Piac utca 10.',
            'delivery_address' => 'Miskolc, Széchenyi utca 30.',
            'recipient_name' => 'Szabó Áron',
            'recipient_phone' => '+36 70 888 9900',
            'status' => JobStatus::Assigned,
            'carrier_id' => $carrier->id,
        ]);

        $response = $this->patchJson("/api/jobs/{$job->id}/status", [
            'status' => JobStatus::Failed->value,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', JobStatus::Failed->value);

        $this->assertDatabaseHas('transport_jobs', [
            'id' => $job->id,
            'status' => JobStatus::Failed->value,
        ]);

        $this->assertDatabaseHas('job_alerts', [
            'job_id' => $job->id,
        ]);
    }
}

