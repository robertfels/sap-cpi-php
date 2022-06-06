<?php
namespace contiva\sapcpiphp;

use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\ClientException as ClientException;
use GuzzleHttp\Exception\RequestException as RequestException;
use GuzzleHttp\Exception\ConnectException as ConnectException;
use GuzzleHttp\Cookie\CookieJar as CookieJar;

class Connection
{
    // Die abgeleitete Klasse zwingen, diese Methoden zu definieren
    private string $hostname;
    private int $port = 443;
    private string $path;
    private array $auth;
    private ?string $error;
    private string $token;
    private CookieJar $cookie;

    function __construct(string $hostname, string $username, string $password, string $path = "/api/v1/", int $port = 443) {
        $this->setUrl($hostname,$port);
        $this->setPath($path);
        $this->setCredentials($username,$password);
    }

    private function setUrl(string $hostname, int $port = 443)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        return true;
    }

    private function setPath(string $path)
    {
        $this->path = $path;
        return true;
    }

    private function setCredentials(string $username, string $password)
    {
        $this->auth = [$username, $password];
        $this->cookie = new CookieJar();
        return $this->auth();
    }

    public function get(string $path) : object|null
    {
        try {
            $this->path = $path;
            $client = new Client(['exception'=>false]);
            $response = $client->request('GET', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'headers' => [
                    'Accept'        => 'application/json'
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return null;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) json_decode( $e->getResponse()->getBody());
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return null;
        }
        return (object) json_decode($response->getBody());
    }

    public function post(string $body,string $path)
    {
        try {
            $this->path = $path;
            $client = new Client(['exceptions'=>false]);
            $response = $client->request('POST', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'cookies' => $this->cookie,
                'body' => $body,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => $this->token,
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return $this->error;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) json_decode( $e->getResponse()->getBody());
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return $this->error;
        }
        return $response->getStatusCode();
    }

    public function put(string $body,string $path)
    {
        try {
            $this->path = $path;
            $client = new Client(['exceptions'=>false]);
            $response = $client->request('PUT', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'cookies' => $this->cookie,
                'body' => $body,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => $this->token,
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return $this->error;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) json_decode( $e->getResponse()->getBody());
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return $this->error;
        }
        return $response->getStatusCode();
    }

    public function delete(string $body,string $path)
    {
        try {
            $this->path = $path;
            $client = new Client(['exceptions'=>false]);
            $response = $client->request('DELETE', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'cookies' => $this->cookie,
                'body' => $body,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => $this->token,
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return $this->error;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) json_decode( $e->getResponse()->getBody());
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return $this->error;
        }
        return $response->getStatusCode();
    }

    public function patch(string $body,string $path)
    {
        try {
            $this->path = $path;
            $client = new Client(['exceptions'=>false]);
            $response = $client->request('PATCH', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'cookies' => $this->cookie,
                'body' => $body,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => $this->token,
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return $this->error;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) json_decode( $e->getResponse()->getBody());
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return $this->error;
        }
        return $response->getStatusCode();
    }

    public function auth() : bool
    {
        try {
            $client = new Client(['exception'=>false,'cookies' => $this->cookie]);
            $response = $client->request('GET', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'headers' => [
                    'Accept'        => 'application/json',
                    'X-CSRF-Token'  => 'Fetch'
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            return false;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return false;
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return false;
        }
        $this->token = $response->getHeader('x-csrf-token')[0];
        return true;
    }
}
