<?php
namespace contiva\sapcpiphp;

class SapCpiHelper extends Connection
{
    // Die abgeleitete Klasse zwingen, diese Methoden zu definieren
    private string $artifactId;
    private string $packageId;
    private string $artifactVersion = "active";
    public object $artifact;
    public ?object $package;

    /**
     * readArtifact
     *
     * @return object
     */
    public function readArtifact(string $artifactId)
    {
        $this->artifactId = $artifactId;
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $this->artifactId . '%27,Version=%27' . $this->artifactVersion . '%27)';
        if ((empty($this->artifact)) || ((!empty($this->artifact->d->Id)) && ($this->artifactId != $this->artifact->d->Id)) || (empty($this->artifact->d->Id)))    
        $this->artifact = $this->get($path);
        return $this->artifact;
    }

    /**
     * createPackage
     *
     * @param  mixed $id
     * @param  mixed $name
     * @param  mixed $shortText
     * @return int
     */
    public function createPackage(string $id, string $name, string $shortText){
        $path = '/api/v1/IntegrationPackages';
        $package['Id'] = $id;
        $package['Name'] = $name;
        $package['ShortText'] = $shortText;
        $body = json_encode($package);
        return $this->post($body,$path);
    } 

    /**
     * readPackage
     *
     * @return object
     */
    public function readPackage(string $packageId)
    {
        $this->packageId = $packageId;
        $path = '/api/v1/IntegrationPackages(%27' . $this->packageId . '%27)';
        if ((empty($this->package)) || ((!empty($this->package->d->Id)) && ($this->packageId != $this->package->d->Id)) || (empty($this->package->d->Id)))
        $this->package = $this->get($path);
        return $this->package;
    }
    
    
}
