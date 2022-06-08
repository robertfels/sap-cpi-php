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
    private ?string $token;
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

    public function get(string $path) : object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");
        
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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (object) json_decode($response->getBody());
    }

    public function download(string $path) : string|object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");
        
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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (string) base64_encode($response->getBody());
    }

    public function post(string $body,string $path) : object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");
        
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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (object) array("status"=>"success");
    }

    public function put(string $body,string $path) : object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");

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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (object) array("status"=>"success");
    }

    public function delete(string $path,string $body = null) : object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");

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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (object) array("status"=>"success");
    }

    public function patch(string $body,string $path) : object
    {
        if ($this->token == null)
        return (object) array("status"=>"error","message"=>"Please auth first!");

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
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>json_decode( $e->getResponse()->getBody()),true);
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return (object) array("status"=>"error","message"=>"Client Error");
        }
        return (object) array("status"=>"success");
    }

    public function auth() : object
    {
        try {
            $client = new Client(['exceptions'=>true,'cookies' => $this->cookie]);
            $response = $client->request('GET', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'headers' => [
                    'Accept'        => 'application/json',
                    'X-CSRF-Token'  => 'Fetch'
                ]
            ]);
        } catch (ConnectException $e){
            $this->error = 111;
            $this->token = null;
            return (object) array("status"=>"error","message"=>"Connect Error");
        } catch (RequestException $e){
            $this->error = 999;
            if ($e->getResponse() != NULL)
            $this->error = $e->getResponse()->getStatusCode();
            $this->token = null;
            switch ($this->error) {
                case 404:
                    return (object) array("status"=>"error","message"=>"Host not found","code"=>$this->error);
                case 401:
                    return (object) array("status"=>"error","message"=>"Credentials not correct","code"=>$this->error);
                case 403:
                    return (object) array("status"=>"error","message"=>"Permissions denied","code"=>$this->error);
                case 999:
                    return (object) array("status"=>"error","message"=>"Undefined Error, maybe SSL","code"=>$this->error);
                default:
                    return (object) array("status"=>"error","message"=>$this->error);
            }
        } catch (ClientException $e){
            //$this->error = $e->getResponse()->getStatusCode();
            $this->token = null;
            return (object) array("status"=>"error","message"=>"Client Error");
        }

        $this->token = $response->getHeader('x-csrf-token')[0];
        return (object) array("status"=>"success");
    }
}
