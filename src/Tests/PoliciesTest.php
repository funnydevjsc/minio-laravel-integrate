<?php

namespace FunnyDev\MinIO\Tests;

use FunnyDev\MinIO\MinIOPolicies;
use GuzzleHttp\Exception\GuzzleException;
use Orchestra\Testbench\TestCase;

class PoliciesTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinIOPolicies::class,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function test(): void
    {
        $instance = new MinIOPolicies();
        $response = $instance->list();
        $this->assertTrue(is_array($response) && isset($response['policies']) && is_array($response['policies']), 'Get list successful');
    }
}