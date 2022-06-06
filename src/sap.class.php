<?php
namespace contiva\sapcpiphp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

class SapCpiHelper extends Connection
{
    // Die abgeleitete Klasse zwingen, diese Methoden zu definieren
    private string $artifactId;
    private string $packageId;
    private string $artifactVersion = "active";
    public object $artifact;
    public object $package;
    
    /**
     * getConnection
     *
     * @return bool
     */
    public function getConnection(): bool
    {   
        return $this->auth();
    }

    /**
     * getArtifact
     *
     * @return object
     */
    public function getArtifact(string $artifactId)
    {
        $this->artifactId = $artifactId;
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $this->artifactId . '%27,Version=%27' . $this->artifactVersion . '%27)';
        if ((empty($this->artifact)) || ((!empty($this->artifact->d->Id)) && ($this->artifactId != $this->artifact->d->Id)) || (empty($this->artifact->d->Id)))    
        $this->artifact = $this->get($path);
        return $this->artifact;
    }

    /**
     * getPackage
     *
     * @return object
     */
    public function getPackage(string $packageId)
    {
        $this->packageId = $packageId;
        $path = '/api/v1/IntegrationPackages(%27' . $this->packageId . '%27)';
        if ((empty($this->package)) || ((!empty($this->package->d->Id)) && ($this->packageId != $this->package->d->Id)) || (empty($this->package->d->Id)))
        $this->package = $this->get($path);
        return $this->package;
    }
}
