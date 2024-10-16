<?php

namespace FunnyDev\MinIO;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class MinIOGroups
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
        return $this->sdk->send('GET', '/api/v1/groups');
    }
    
    /**
     * @throws GuzzleException
     */
    public function create(string $name, array $members=[]): array|string
    {
        $param = [
            "group" => $name,
            "members" => $members
        ];

        return $this->sdk->send('POST', '/api/v1/groups', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function read(string $name, string $attribute=''): array|string
    {
        return $this->sdk->send('GET', '/api/v1/groups/'.$name.'/'.$attribute);
    }

    /**
     * @throws GuzzleException
     */
    public function update(string $name, bool $enable=true, array $members=[]): array|string
    {
        $param = [
            "group" => $name,
            "members" => $members,
            "enable" => $enable
        ];

        return $this->sdk->send('PUT', '/api/v1/groups/'.$name.'/', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $name): array|string
    {
        $this->update($name, false);
        return $this->sdk->send('DELETE', '/api/v1/groups/'.$name);
    }

    /**
     * @throws GuzzleException
     */
    public function update_policies(string $name, array $policies=[]): array|string
    {
        $param = [
            "group" => $name,
            "name" => $policies,
            "users" => null
        ];

        return $this->sdk->send('POST', '/api/v1/set-policy-multi', $param);
    }
}
