<?php

namespace Tests\Unit;

use App\Admin;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /** @test */
    public function password_and_remember_token_fields_are_not_exposed_when_casting_model_to_array_or_json()
    {
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);

        $array_output = $admin->toArray();

        $this->assertFalse(array_key_exists('password', $array_output));
        $this->assertFalse(array_key_exists('remember_token', $array_output));

        $json_output = json_decode($admin->toJson());

        $this->assertFalse(array_key_exists('password', $json_output));
        $this->assertFalse(array_key_exists('remember_token', $json_output));
    }
}
