<?php

namespace Tests\Unit;

use App\Models\Profile;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected array $headers = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->headers = [
            'Authorization' => 'Bearer bM8a7Wm2K1p9Xr4Fq6J5Zt2Vn8Yc3DbQ',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function testCreateProfile()
    {
        $payload = [
            'name' => 'Mario',
            'surname' => 'Rossi',
            'phone' => '1234567890',
        ];

        $response = $this->json('POST', '/api/profiles', $payload, $this->headers);

        if ($response->response->getStatusCode() !== 201) {
            echo "\nResponse Status Code: " . $response->response->getStatusCode();
            echo "\nResponse Body: " . $response->response->getContent();
            echo "\nPayload: " . json_encode($payload);
        }

        $response->seeStatusCode(201)
            ->seeJsonStructure([
                'id',
                'name',
                'surname',
                'phone',
                'created_at',
                'updated_at',
            ]);
    }

    public function testReadProfile()
    {
        $profile = Profile::factory()->create();

        $this->get("/api/profiles/{$profile->id}", $this->headers)
            ->seeStatusCode(200)
            ->seeJson([
                'id' => $profile->id,
                'name' => $profile->name,
                'surname' => $profile->surname,
                'phone' => $profile->phone,
            ]);
    }

    public function testUpdateProfile()
    {
        $profile = Profile::factory()->create();

        $payload = [
            'name' => 'Luigi',
            'surname' => 'Bianchi',
        ];

        $response = $this->json('PUT', "/api/profiles/{$profile->id}", $payload, $this->headers);

        if ($response->response->getStatusCode() !== 200) {
            echo "\nResponse Status Code: " . $response->response->getStatusCode();
            echo "\nResponse Body: " . $response->response->getContent();
            echo "\nPayload: " . json_encode($payload);
        }

        $response->seeStatusCode(200)
            ->seeJson([
                'id' => $profile->id,
                'name' => 'Luigi',
                'surname' => 'Bianchi',
            ]);
    }

    public function testDeleteProfile()
    {
        $profile = Profile::factory()->create();

        $this->json('DELETE', "/api/profiles/{$profile->id}", [], $this->headers)
            ->seeStatusCode(200)
            ->seeJson([
                'message' => 'Profile deleted successfully',
            ]);
    }

    public function testAuthentication()
    {
        $this->post('/api/profiles', [], ['Content-Type' => 'application/json'])
            ->seeStatusCode(401)
            ->seeJson([
                'error' => 'Unauthorized',
            ]);
    }
}
