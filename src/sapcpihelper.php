<?php
namespace contiva\sapcpiphp;

class SapCpiHelper extends Connection
{
    // Die abgeleitete Klasse zwingen, diese Methoden zu definieren
    private string $artifactId;
    private string $packageId;
    private string $artifactVersion = "active";
    public ?object $artifact;
    public ?object $package;
    
    /**
     * readArtifact
     *
     * @param  string $artifactId
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
     * @param Package $package
     * @return object
     */
    public function createPackage(Package $package){
        $path = '/api/v1/IntegrationPackages';
        $body = json_encode($package);
        return $this->post($body,$path);
    } 
        
    /**
     * readPackage
     *
     * @param  string $packageId
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
    
    /**
     * readValueMappings
     *
     * @param  string $valueMappingId
     * @return object
     */
    public function readValueMappings(string $packageId)
    {
        $path = '/api/v1/IntegrationPackages(%27' . $packageId . '%27)/ValueMappingDesigntimeArtifacts';
        return $this->get($path);
    }

    /**
     * deletePackage
     *
     * @param  Package $package
     * @return object
     */
    public function deletePackage(Package $package) {
        $path = '/api/v1/IntegrationPackages(%27' . $package->Id . '%27)';
        return $this->delete($path);
    }
    
    /**
     * updatePackage
     *
     * @param  Package $package
     * @return object
     */
    public function updatePackage(Package $package) { 
        $path = '/api/v1/IntegrationPackages(%27' . $package->Id . '%27)';
        $body = json_encode(strval($package));
        return $this->put($body,$path);
    }
    
}
