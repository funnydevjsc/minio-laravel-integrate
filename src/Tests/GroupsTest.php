<?php

namespace FunnyDev\MinIO\Tests;

use FunnyDev\MinIO\MinIOGroups;
use GuzzleHttp\Exception\GuzzleException;
use Orchestra\Testbench\TestCase;

class GroupsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinIOGroups::class,
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function test(): void
    {
        $instance = new MinIOGroups();
        $response = $instance->list();
        $this->assertTrue(is_array($response) && isset($response['groups']) && is_array($response['groups']), 'Get list successful');
    }
}