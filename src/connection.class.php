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
    private CookieJar $jar;
    private array $auth;
    private ?string $error;
    private string $token;
    private string $cookie;

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
        $this->jar = new CookieJar();
        return $this->auth();
    }

    public function get(string $path) : object
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
            return false;
        } catch (RequestException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return false;
        } catch (ClientException $e){
            $this->error = $e->getResponse()->getStatusCode();
            return false;
        }
        return (object) json_decode($response->getBody());
    }

    public function auth() : bool
    {
        try {
            $client = new Client(['exception'=>false,'cookies' => $this->jar]);
            $response = $client->request('GET', 'https://'.$this->hostname.':'.$this->port.$this->path, [
                'auth' => $this->auth,
                'headers' => [
                    'Accept'        => 'application/json',
                    'x-csrf-token'  => 'Fetch'
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
