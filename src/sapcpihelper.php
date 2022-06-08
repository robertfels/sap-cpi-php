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
    
    /*
     *  PACKAGES
     */

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
     * readPackages
     *
     * @return object
     */
    public function readPackages()
    {
        $path = '/api/v1/IntegrationPackages';
        return $this->get($path);
    }

    /**
     * readFlowsOfPackage
     *
     * @param  string $packageId
     * @return object
     */
    public function readFlowsOfPackage(string $packageId)
    {
        $path = '/api/v1/IntegrationPackages(%27' . $packageId . '%27)/IntegrationDesigntimeArtifacts';
        return $this->$this->get($path);
    }

    /**
     * readValueMapsOfPackage
     *
     * @param  string $packageId
     * @return object
     */
    public function readValueMapsOfPackage(string $packageId)
    {
        $path = '/api/v1/IntegrationPackages(%27' . $packageId . '%27)/ValueMappingDesigntimeArtifacts';
        return $this->$this->get($path);
    }
    
    /**
     * updatePackage
     *
     * @param  Package $package
     * @return object
     */
    public function updatePackage(Package $package) { 
        $path = '/api/v1/IntegrationPackages(%27' . $package->Id . '%27)';
        $body = json_encode($package);
        return $this->put($body,$path);
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

    /*
     *  ARTIFACTS
     */

    /**
     * createArtifact
     *
     * @param Artifact $artifact
     * @return object
     */
    public function createArtifact(Artifact $artifact){
        $path = '/api/v1/IntegrationDesigntimeArtifacts';
        $body = json_encode($artifact);
        return $this->post($body,$path);
    } 
        
    /**
     * readArtifact
     *
     * @param  string $artifactId
     * @param  string $version
     * @return object
     */
    public function readArtifact(string $artifactId,string $version="active")
    {
        $this->artifactId = $artifactId;
        $this->artifactVersion = $version;
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $this->artifactId . '%27,Version=%27' . $this->artifactVersion . '%27)';
        if ((empty($this->artifact)) || ((!empty($this->artifact->d->Id)) && ($this->artifactId != $this->artifact->d->Id)) || (empty($this->artifact->d->Id)))    
        $this->artifact = $this->get($path);
        return $this->artifact;
    }
    
    /**
     * downloadArtifact
     *
     * @param  Artifact $artifact
     * @return object
     */
    public function downloadArtifact(Artifact $artifact) : object
    {
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifact->Id . '%27,Version=%27' . $artifact->Version . '%27)/$value';
        $download = $this->download($path);
        
        if (is_object($download))
        return $download;

        $artifact->ArtifactContent = $this->download($path);
        return $artifact;
    }

    /**
     * deployArtifact
     *
     * @param Artifact $artifact
     * @return object
     */
    public function deployArtifact(Artifact $artifact){
        $path = '/api/v1/DeployIntegrationDesigntimeArtifact?Id=%27'.$artifact->Id.'%27&Version=%27'.$artifact->Version.'%27';
        $body = "";
        return $this->post($body,$path);
    }
    
    /**
     * updateArtifact
     *
     * @param  Artifact $artifact
     * @return object
     */
    public function updateArtifact(Artifact $artifact) { 
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifact->Id . '%27,Version=%27' . $artifact->Version . '%27)';
        $body = json_encode($artifact);
        return $this->put($body,$path);
    }
        
    /**
     * deleteArtifact
     *
     * @param  Artifact $artifact
     * @return object
     */
    public function deleteArtifact(Artifact $artifact) {
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifact->Id . '%27,Version=%27' . $artifact->Version . '%27)';
        return $this->delete($path);
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
    
}
