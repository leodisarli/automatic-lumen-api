<?php

namespace Tests\Feature\Domains\Auth;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\Feature\TestCaseFeature;

class AuthGenerateControllerTest extends TestCaseFeature
{
    use DatabaseMigrations;

    /**
     * @covers \App\Domains\Auth\Http\Controllers\AuthGenerateController::__construct
     * @covers \App\Domains\Auth\Http\Controllers\AuthGenerateController::process
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::process
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::generateToken
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::getFromToken
     */
    public function testAuthenticate()
    {
        $body = [
            'token' => '379ba1a99843b24ada4b4e068afa0a872f11392859424cab1b86764c6ee7cddf',
            'secret' => 'ba135548fce772e5f82f3f477a19b4479f2430c7d0933789ac427c2f97c079bf',
        ];

        $this->json('POST', '/auth/generate', $body);

        $response = json_decode($this->response->getContent(), true);

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($response['data']['token']);
        $this->assertNotNull($response['data']['valid_until']);
    }

    /**
     * @covers \App\Domains\Auth\Http\Controllers\AuthGenerateController::__construct
     * @covers \App\Domains\Auth\Http\Controllers\AuthGenerateController::process
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::process
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::generateToken
     * @covers \App\Domains\Auth\Businesses\AuthGenerateBusiness::getFromToken
     */
    public function testAuthenticateInvalidCredencials()
    {
        config(['tokens.data' => null]);

        $body = [
            'token' => 'c50683082e1c741e81aba9246e63095e2e5a19b7ea296f3dcb06f557b9f626e8',
            'secret' => '5c6eec9722d3e008afb301d3d6b18bf3ef2008230910f995b590d322635b8adc',
        ];

        $this->json('POST', '/auth/generate', $body);

        $response = json_decode($this->response->getContent(), true);

        $this->assertEquals(401, $this->response->getStatusCode());
        $this->assertEquals('Invalid credentials', $response['message']);
    }
}
