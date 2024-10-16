<?php

namespace FunnyDev\MinIO\Tests;

use FunnyDev\MinIO\MinIOSdk;
use GuzzleHttp\Exception\GuzzleException;
use Orchestra\Testbench\TestCase;

class SdkTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinIOSdk::class,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function test(): void
    {
        $instance = new MinIOSdk();
        $this->assertTrue($instance->login(), 'Login successful');
    }
}