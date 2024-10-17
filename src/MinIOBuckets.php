<?php

namespace FunnyDev\MinIO;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class MinIOBuckets
{
    private MinIOSdk $sdk;
    public array $url = [];

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function __construct(string $server='', string $cookie='')
    {
        $this->sdk = new MinIOSdk($server, $cookie);
        if (! $this->sdk->login()) {
            throw new Exception('Failed to login to MinIO');
        }
    }

    /**
     * @throws GuzzleException
     */
    public function list(): array|string
    {
        return $this->sdk->send('GET', '/api/v1/buckets');
    }
    
    /**
     * @throws GuzzleException
     */
    public function create(string $name, int $quota=0, int $retention=0, string $retention_mode='compliance', bool $locking=false, bool $versioning=false, bool $exclude=false, array $exclude_prefixes=[]): array|string
    {
        if ($locking) {
            $versioning = true;
        }

        $param = [
            "name" => $name,
            "versioning" => [
                "enabled" => $versioning,
                "excludePrefixes" => $exclude_prefixes,
                "excludeFolders" => $exclude
            ],
            "locking" => $locking
        ];

        if ($quota > 0) {
            $param['quota'] = [
                "enabled" => true,
                "quota_type" => "hard",
                "amount" => $quota
            ];
        }

        if ($retention > 0) {
            $param['retention'] = [
                "mode" => $retention_mode,
                "unit" => "days",
                "validity" => $retention
            ];
        }
        return $this->sdk->send('POST', '/api/v1/buckets', $param);
    }

    /**
     * @throws GuzzleException
     */
    public function read(string $name, string $attribute=''): array|string
    {
        return $this->sdk->send('GET', '/api/v1/buckets/'.$name.'/'.$attribute);
    }

    /**
     * @throws GuzzleException
     */
    public function update(string $name, string $attribute, array $data=[]): array|string
    {
        if ($attribute == 'quota') {
            $param = [
                "enabled" => (bool) $data['quota'] > 0,
                "quota_type" => "hard",
                "amount" => $data['quota']
            ];
            return $this->sdk->send('PUT', '/api/v1/buckets/'.$name.'/quota', $param);
        }

        return ['status' => false, 'message' => 'Attribute '.$attribute.' is not supported.'];
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $name): array|string
    {
        return $this->sdk->send('DELETE', '/api/v1/buckets/'.$name);
    }
}
