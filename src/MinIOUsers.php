<?php

namespace FunnyDev\MinIO;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class MinIOUsers
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
        return $this->sdk->send('GET', '/api/v1/users');
    }
    
    /**
     * @throws GuzzleException
     */
    public function create(string $access_key, string $secret_key, array $groups=[], array $policies=[]): array|string
    {
        $param = [
            "accessKey" => $access_key,
            "secretKey" => $secret_key,
            "groups" => $groups,
            "policies" => $policies
        ];

        return $this->sdk->send('POST', '/api/v1/users', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function read(string $name, string $attribute=''): array|string
    {
        return $this->sdk->send('GET', '/api/v1/users/'.$name.'/'.$attribute);
    }

    /**
     * @throws GuzzleException
     */
    public function update(string $access_key, string $secret_key, bool $enable=true, array $groups=[], array $policies=[]): array|string
    {
        $param = [
            "secretKey" => $secret_key,
            "groups" => $groups,
            "policies" => $policies,
            "enabled" => $enable
        ];

        return $this->sdk->send('PUT', '/api/v1/users/'.$access_key.'/', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $name): array|string
    {
        return $this->sdk->send('DELETE', '/api/v1/users/'.$name);
    }

    /**
     * @throws GuzzleException
     */
    public function update_password(string $user, string $secret_key): array|string
    {
        $param = [
            "selectedUser" => $user,
            "secretKey" => $secret_key
        ];

        return $this->sdk->send('POST', '/api/v1/account/change-user-password', $param);
    }
}
