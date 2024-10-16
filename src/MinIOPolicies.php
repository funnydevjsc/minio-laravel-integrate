<?php

namespace FunnyDev\MinIO;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class MinIOPolicies
{
    private MinIOSdk $sdk;
    public array $url = [];

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function __construct(string $server='', string $accessKey='', string $secretKey='')
    {
        $this->sdk = new MinIOSdk($server, $accessKey, $secretKey);
        if (! $this->sdk->login()) {
            throw new Exception('Failed to login to MinIO');
        }
    }

    /**
     * @throws GuzzleException
     */
    public function list(): array|string
    {
        return $this->sdk->send('GET', '/api/v1/policies');
    }
    
    /**
     * @throws GuzzleException
     */
    public function create(string $name, string $rule): array|string
    {
        $param = [
            "name" => $name,
            "policy" => $rule
        ];

        return $this->sdk->send('POST', '/api/v1/policies', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function read(string $name, string $attribute=''): array|string
    {
        return $this->sdk->send('GET', '/api/v1/policies/'.$name.'/'.$attribute);
    }

    /**
     * @throws GuzzleException
     */
    public function update(string $name, string $rule): array|string
    {
        $param = [
            "name" => $name,
            "policy" => $rule
        ];

        return $this->sdk->send('POST', '/api/v1/policies'.$name.'/', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $name): array|string
    {
        $this->update($name, false);
        return $this->sdk->send('DELETE', '/api/v1/policies/'.$name);
    }
}
