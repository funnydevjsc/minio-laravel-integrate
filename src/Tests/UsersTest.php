<?php

namespace FunnyDev\MinIO\Tests;

use FunnyDev\MinIO\MinIOUsers;
use GuzzleHttp\Exception\GuzzleException;
use Orchestra\Testbench\TestCase;

class UsersTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinIOUsers::class,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function test(): void
    {
        $instance = new MinIOUsers();
        $response = $instance->list();
        $this->assertTrue(is_array($response) && isset($response['users']) && is_array($response['users']), 'Get list successful');
    }
}