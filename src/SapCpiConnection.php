<?php

namespace contiva\sapcpiphp;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class SapCpiConnection {


    private CookieJar $cookie;
    private string $hostname;
    private int $port;
    private string $username;
    private string $password;

    public function __construct(string $hostname,int $port,string $username,string $password)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->cookie = new CookieJar();

        $client = new Client(['cookies' => $this->cookie]);
        
        $response = $client->request('GET', 'https://' . $this->hostname . ':' . $this->port . '/api/v1', [
            'auth' => [$this->username,$this->password],
            'cookies' => $this->cookie,
            'connect_timeout' => 5,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'x-csrf-token'  => 'fetch',
            ]
        ]);
        
        $this->token = $response->getHeader('x-csrf-token')[0];
    }

    public function request(string $method = ('GET'|'POST'|'FETCH'|'PUT'|'MERGE'),string $path = null,string $body = null) {

            $client = new Client(['cookies' => $this->cookie]);

            $sessionParams = array(
                'auth' => [$this->username,$this->password],
                'cookies' => $this->cookie,
                'connect_timeout' => 5,
                'body' => $body,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => $this->token,
                ]
            );
            if ($body == null)
            unset($sessionParams['body']);

            $response = $client->request($method, 'https://' . $this->hostname . ':' . $this->port . '/api/v1' . $path, $sessionParams);

            return $response;
    }

}