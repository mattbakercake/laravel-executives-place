<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Str;

class UserControllerTest extends TestCase
{
    public function test_index_returns_resources()
    {

        $this->json('get', '/api/users')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'rate_hour',
                            'currency_iso'
                        ]
                    ]
                ]
            );
    }

    public function test_store_creates_user()
    {

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'rate_hour' => 40,
            'currency_iso' => 'GBP'
        ];
        $this->json('post', 'api/users', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'email',
                    'rate_hour',
                    'currency_iso'
                ]
            );
        $this->assertDatabaseHas('users', $payload);
    }

    public function test_show_returns_user_details()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::password(),
            'rate_hour' => 250,
            'currency_iso' => 'GBP'
        ]);

        $this->json('get', 'api/users/' . $user->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'rate_hour' => $user->rate_hour,
                        'currency_iso' => $user->currency_iso
                    ]
                ]
            );
    }

    public function test_update_modifies_user_details()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::password(),
            'rate_hour' => 20,
            'currency_iso' => 'GBP'
        ]);
        $payload = [
            'rate_hour' => 75,
        ];

        $this->json('put', 'api/users/' . $user->id, $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'rate_hour' => 75,
                    'currency_iso' => $user->currency_iso
                ]
            );
    }

    public function test_destroy_deletes_user()
    {
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::password(),
            'rate_hour' => 20,
            'currency_iso' => 'GBP'
        ];
        $user = User::create($payload);

        $this->json('delete', 'api/users/' . $user->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('users', $payload);
    }

    public function test_show_can_call_rate_service_and_convert_currency()
    {
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::password(),
            'rate_hour' => 100,
            'currency_iso' => 'EUR'
        ];
        $user = User::create($payload);

        $this->json('get', 'api/users/' . $user->id . '/USD')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'currency_iso' => 'USD'
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    'rate_hour' => 100
                ]
            ]);
    }
}
