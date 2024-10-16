<?php

namespace FunnyDev\MinIO\Tests;

use FunnyDev\MinIO\MinIOBuckets;
use GuzzleHttp\Exception\GuzzleException;
use Orchestra\Testbench\TestCase;

class BucketsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinIOBuckets::class,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function test(): void
    {
        $instance = new MinIOBuckets();
        $response = $instance->list();
        $this->assertTrue(is_array($response) && isset($response['buckets']) && is_array($response['buckets']), 'Get list successful');
    }
}