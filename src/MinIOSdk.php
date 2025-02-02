<?php

namespace FunnyDev\MinIO;

use Exception;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class MinIOSdk
{
    private string $server;
    private string $cookie;
    private array $header;
    private CookieJar $cookies;
    private Client $client;

    public function __construct(string $server='', string $cookie='')
    {
        $this->server = empty($server) ? Config::get('minio.server') : $server;
        $this->cookie = empty($cookie) ? Config::get('minio.cookie') : $cookie;
        $this->header = [
            'connection' => 'keep-alive',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
            'content-type' => 'application/json',
            'sec-ch-ua' => '"Google Chrome";v="129", "Not=A?Brand";v="8", "Chromium";v="129"',
            'priority' => 'u=1, i',
            'sec-ch-ua-mobile' => '?0',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'sec-ch-ua-platform' => '"macOS"',
            'cookie' => $this->cookie
        ];
        $this->cookies = new CookieJar();
        $this->client = new Client();
    }

    public function parse($data): array
    {
        try {
            if (!$data) {
                return [];
            }
            $tmp = json_decode(json_encode($data, true), true);
            if (!is_array($tmp)) {
                $tmp = json_decode($tmp, true);
            }
            return $tmp ?? ['status' => false, 'message' => 'The post param has some wrong type of values'];
        } catch (Exception) {
            return ['status' => false, 'message' => 'The post param has some wrong type of values'];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function send(string $method='GET', string $uri='', array $param=[], string $response='json'): array|string
    {
        if (!str_starts_with($uri, 'https://') && !str_starts_with($uri, 'http://')) {
            $url = $this->server . $uri;
        } else {
            $url = $uri;
        }
        if ($method == 'GET') {
            if (count($param) > 0) {
                $url .= '?' . http_build_query($param);
            }
        }
        $res = $this->client->request($method, $url, [
            'timeout' => 60,
            'headers' => $this->header,
            'cookies' => $this->cookies,
            'json' => $param,
            'allow_redirects' => false,
            'http_errors' => true
        ]);
        foreach ($res->getHeader('Set-Cookie') as $key => $header) {
            $cookie = SetCookie::fromString($header);
            $this->cookies->setCookie($cookie);
        }
        if ($res->getStatusCode() == 200) {
            if ($response == 'json') {
                return $this->parse($res->getBody()->getContents());
            }
            return $res->getBody()->getContents();
        } else if ($res->getStatusCode() > 200 && $res->getStatusCode() < 400) {
            if ($response == 'json') {
                return ['status' => true, 'message' => 'Action completed successfully'];
            }
            return '';
        } else {
            return ['status' => false, 'code' => $res->getStatusCode(), 'message' => $res->getBody()->getContents()];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function login(): bool
    {
        $response = $this->send('GET', '/api/v1/session');
        if ($response['status'] === 'ok') {
            return true;
        }
        return false;
    }
}