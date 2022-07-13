<?php

namespace contiva\sapcpiphp;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Event\ErrorEvent;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use RuntimeException;

use function PHPUnit\Framework\throwException;

/**
 * SapCpiConnection
 */
class SapCpiConnection
{

    private CookieJar $cookie;  //auto
    private string $hostname;   //constr.
    private int $port;          //constr.
    private string $username;   //constr.
    private string $password;   //constr.
    private ?string $token;      //auto

    /**
     * __construct
     * Needs all params for instancing
     *
     * @param  string $hostname
     * @param  int $port
     * @param  string $username
     * @param  string $password
     * @return void
     */
    public function __construct(string $hostname, int $port, string $username, string $password)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->cookie = new CookieJar();
        $this->token = null;

        if ($this->auth() != true) {
            throw new RuntimeException("Connection was not successful");
        }
    }

    /**
     * request()
     * Every request against sap api
     *
     * @param  string $method
     * @param  string $path
     * @param  string $body
     * @return ResponceInterface
     */
    public function request(string $method = ('GET' | 'POST' | 'FETCH' | 'PUT' | 'MERGE' | 'DELETE'), string $path = null, string $body = null): ResponseInterface
    {
        $retry = false;
        sendrequest:
        try {
            $client = new Client(['cookies' => $this->cookie]);

            if ($this->token == null)
            $this->auth();

            $options = array(
                'auth' => [$this->username, $this->password],
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
                unset($options['body']);

            $response = $client->request(
                $method, 
                'https://' . $this->hostname . ':' . $this->port . '/api/v1' . $path, 
                $options
            );

            return $response;
        } catch (ClientException $e) {
            $errorCodes = array("403", "500", "503", "429");
            if ((in_array($e->getResponse()->getStatusCode(),$errorCodes)) && ($retry != true)) {
                $this->auth();
                $retry = true;
                goto sendrequest;
            } else {
                throw $e;
            }   
        }
    }
    
    /**
     * auth()
     * Authentication with FETCH x-csrf-token
     *
     * @return void
     */
    private function auth()
    {
        $client = new Client(['cookies' => $this->cookie]);

        $response = $client->request('GET', 'https://' . $this->hostname . ':' . $this->port . '/api/v1', [
            'auth' => [$this->username, $this->password],
            'cookies' => $this->cookie,
            'connect_timeout' => 5,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'x-csrf-token'  => 'fetch',
            ]
        ]);

        $this->token = $response->getHeader('x-csrf-token')[0];

        return true;
    }
    
    /**
     * package instance
     *
     * @param  string $Id
     * @return SapCpiPackage
     */
    public function package(string $Id = null,string $Name = null,string $ShortText = null) {
        $obj = new SapCpiPackage($this, $Id, $Name, $ShortText);
        return $obj;
    }
    
    /**
     * artifact
     *
     * @param  string $Id
     * @return SapCpiArtifact
     */
    public function artifact($Id = null) {
        $obj = new SapCpiArtifact($this, $Id);
        return $obj;
    }
}
