<?php

namespace Tests\Unit;

use App\Http\Controllers\ProfileController;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use App\Traits\SanitizesPhoneNumbers;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations, SanitizesPhoneNumbers;

    protected array $headers = [];
    protected ProfileController $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->headers = [
            'Authorization' => 'Bearer bM8a7Wm2K1p9Xr4Fq6J5Zt2Vn8Yc3DbQ',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $this->controller = new ProfileController(new ProfileRepository());
    }

    public function testCreateProfile()
    {
        $payload = [
            'name' => 'Mario',
            'surname' => 'Rossi',
            'phone' => '1234567890',
        ];

        $response = $this->json('POST', '/api/profiles', $payload, $this->headers);

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

    public function testCreateProfileValidationFailure()
    {
        $payload = [
            'surname' => 'Rossi',
            'phone' => '1234567890',
        ];

        $response = $this->json('POST', '/api/profiles', $payload, $this->headers);

        $response->seeStatusCode(422)
            ->seeJsonStructure(['error', 'details']);
    }

    public function testReadProfile()
    {
        $profile = Profile::factory()->create();

        $this->get("/api/profiles/$profile->id", $this->headers)
            ->seeStatusCode(200)
            ->seeJson([
                'id' => $profile->id,
                'name' => $profile->name,
                'surname' => $profile->surname,
                'phone' => $profile->phone,
            ]);
    }

    public function testReadProfileNotFound()
    {
        $this->get('/api/profiles/99999', $this->headers)
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Profile not found']);
    }

    public function testUpdateProfile()
    {
        $profile = Profile::factory()->create();

        $payload = [
            'name' => 'Luigi',
            'surname' => 'Bianchi',
        ];

        $response = $this->json('PUT', "/api/profiles/$profile->id", $payload, $this->headers);

        $response->seeStatusCode(200)
            ->seeJson([
                'id' => $profile->id,
                'name' => 'Luigi',
                'surname' => 'Bianchi',
            ]);
    }

    public function testUpdateProfileNotFound()
    {
        $payload = [
            'name' => 'Luigi',
            'surname' => 'Bianchi',
        ];

        $this->json('PUT', '/api/profiles/99999', $payload, $this->headers)
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Profile not found']);
    }

    public function testUpdateProfileValidationFailure()
    {
        $profile = Profile::factory()->create();

        $payload = [
            'name' => '',
            'surname' => 'Bianchi',
        ];

        $response = $this->json('PUT', "/api/profiles/$profile->id", $payload, $this->headers);

        $response->seeStatusCode(422)
            ->seeJsonStructure(['error', 'details']);
    }

    public function testDeleteProfile()
    {
        $profile = Profile::factory()->create();

        $this->json('DELETE', "/api/profiles/$profile->id", [], $this->headers)
            ->seeStatusCode(200)
            ->seeJson([
                'message' => 'Profile deleted successfully',
            ]);
    }

    public function testDeleteProfileNotFound()
    {
        $this->json('DELETE', '/api/profiles/99999', [], $this->headers)
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Profile not found']);
    }

    public function testAuthentication()
    {
        $this->post('/api/profiles', [], ['Content-Type' => 'application/json'])
            ->seeStatusCode(401)
            ->seeJson([
                'error' => 'Unauthorized',
            ]);
    }

    public function testSanitizePhoneNumber()
    {
        $phoneNumbers = [
            '+393391234567' => '3391234567',
            '1234567890' => '1234567890',
        ];

        foreach ($phoneNumbers as $input => $expected) {
            $this->assertEquals($expected, $this->sanitizePhoneNumber($input));
        }
    }
}
