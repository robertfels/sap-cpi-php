<?php

namespace contiva\sapcpiphp;

use DateTime;
use RuntimeException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use function PHPUnit\Framework\throwException;

use Psr\Http\Message\{RequestInterface, ResponseInterface};
use GuzzleHttp\{Client, HandlerStack, Middleware, RetryMiddleware};

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

        $maxRetries = 3;
        $errors = array(429,500,503);

        $decider = function(int $retries, RequestInterface $request, ResponseInterface $response = null) use ($maxRetries,$errors) : bool {
            return 
                $retries < $maxRetries
                && null !== $response 
                && (in_array($response->getStatusCode(), $errors, true));
        };

        $delay = function(int $retries, ResponseInterface $response) : int {
            if (!$response->hasHeader('Retry-After')) {
                return RetryMiddleware::exponentialDelay($retries);
            }

            $retryAfter = $response->getHeaderLine('Retry-After');

            if (!is_numeric($retryAfter)) {
                $retryAfter = (new DateTime($retryAfter))->getTimestamp() - time();
            }

            return (int) $retryAfter * 1000;
        };

        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($decider, $delay));

        $retry = false;
        sendrequest:
        try {
            $client = new Client(['cookies' => $this->cookie,'handler'  => $stack,'connect_timeout' => 5]);

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

        $maxRetries = 3;
        $errors = array(429,500,503);

        $decider = function(int $retries, RequestInterface $request, ResponseInterface $response = null) use ($maxRetries,$errors) : bool {
            return 
                $retries < $maxRetries
                && null !== $response 
                && (in_array($response->getStatusCode(), $errors, true));
        };

        $delay = function(int $retries, ResponseInterface $response) : int {
            if (!$response->hasHeader('Retry-After')) {
                return RetryMiddleware::exponentialDelay($retries);
            }

            $retryAfter = $response->getHeaderLine('Retry-After');

            if (!is_numeric($retryAfter)) {
                $retryAfter = (new DateTime($retryAfter))->getTimestamp() - time();
            }

            return (int) $retryAfter * 1000;
        };

        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($decider, $delay));


        $client = new Client(['cookies' => $this->cookie,'handler'  => $stack,'connect_timeout' => 5]);

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

    public function lisArtifacts() : array {
        $result = $this->request("GET","/IntegrationRuntimeArtifacts");
        if ($result = json_decode($result->getBody())) {
            return $this->sort($result->d->results);
        } else {
            return null;
        }
    }

    private function sort($input) {
        $input = json_decode(json_encode($input), true);
        array_multisort( array_column($input, "Name"), SORT_ASC, $input );
        $input = json_decode(json_encode($input));
        return $input;
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
