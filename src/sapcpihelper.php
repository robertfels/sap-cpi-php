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
    public function createPackage(Package $package) : object
    {
        $path = '/api/v1/IntegrationPackages';
        $body = json_encode($package);
        return $this->post($body, $path);
    }

    /**
     * readPackage
     *
     * @param  string $packageId
     * @return object
     */
    public function readPackage(string $packageId) : object
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
    public function readPackages() : object
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
    public function readFlowsOfPackage(string $packageId) : object
    {
        $path = '/api/v1/IntegrationPackages(%27' . $packageId . '%27)/IntegrationDesigntimeArtifacts';
        return $this->get($path);
    }

    /**
     * readValueMapsOfPackage
     *
     * @param  string $packageId
     * @return object
     */
    public function readValueMapsOfPackage(string $packageId) : object
    {
        $path = '/api/v1/IntegrationPackages(%27' . $packageId . '%27)/ValueMappingDesigntimeArtifacts';
        return $this->get($path);
    }

    /**
     * updatePackage
     *
     * @param  Package $package
     * @return object
     */
    public function updatePackage(Package $package) : object
    {
        $path = '/api/v1/IntegrationPackages(%27' . $package->Id . '%27)';
        $body = json_encode($package);
        return $this->put($body, $path);
    }

    /**
     * deletePackage
     *
     * @param  Package $package
     * @return object
     */
    public function deletePackage(Package $package) : object
    {
        $package->Version = 'active';
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
    public function createArtifact(Artifact $artifact) : object
    {
        unset($artifact->Version,$artifact->Sender,$artifact->Receiver);
        $path = '/api/v1/IntegrationDesigntimeArtifacts';
        $body = json_encode($artifact);
        return $this->post($body, $path);
    }

    /**
     * readArtifact
     *
     * @param  string $artifactId
     * @param  string $version
     * @return object
     */
    public function readArtifact(string $artifactId, string $version = "active") : object
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
    public function downloadArtifact(Artifact $artifact): object
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
    public function deployArtifact(Artifact $artifact) : object
    {
        $path = '/api/v1/DeployIntegrationDesigntimeArtifact?Id=%27' . $artifact->Id . '%27&Version=%27' . $artifact->Version . '%27';
        $body = "";
        return $this->post($body, $path);
    }

    /**
     * updateArtifact
     *
     * @param  Artifact $artifact
     * @return object
     */
    public function updateArtifact(Artifact $artifact) : object
    {
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifact->Id . '%27,Version=%27' . $artifact->Version . '%27)';
        $body = json_encode($artifact);
        return $this->put($body, $path);
    }

    /**
     * deleteArtifact
     *
     * @param  Artifact $artifact
     * @return object
     */
    public function deleteArtifact(Artifact $artifact) : object
    {
        $artifact->Version = 'active';
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifact->Id . '%27,Version=%27' . $artifact->Version . '%27)';
        return $this->delete($path);
    }

    /*
     *  VALUE MAPPINGS
     */

    /**
     * readValueMappings
     *
     * @return object
     */
    public function readValueMappings() : object
    {
        $path = '/api/v1/ValueMappingDesigntimeArtifacts';
        return $this->get($path);
    }

    /**
     * readValueMapping
     *
     * @param  string $valueMapId
     * @param  string $version
     * @return object
     */
    public function readValueMapping(string $valueMapId, string $version = "active") : object
    {
        $path = '/api/v1/ValueMappingDesigntimeArtifacts(Id=%27' . $valueMapId . '%27,Version=%27' . $version . '%27)';
        return $this->get($path);
    }

    /**
     * downloadValueMapping
     *
     * @param  ValueMapping $valueMapping
     * @return object
     */
    public function downloadValueMapping(ValueMapping $valueMapping): object
    {
        $path = '/api/v1/ValueMappingDesigntimeArtifacts(Id=%27' . $valueMapping->Id . '%27,Version=%27' . $valueMapping->Version . '%27)/$value';
        $download = $this->download($path);

        if (is_object($download))
            return $download;

        $valueMapping->ArtifactContent = $this->download($path);
        return $valueMapping;
    }

    /**
     * uploadValueMapping
     *
     * @param  ValueMapping $valueMapping
     * @return object
     */
    public function uploadValueMapping(ValueMapping $valueMapping): object
    {
        $path = '/api/v1/ValueMappingDesigntimeArtifacts';
        $body = $valueMapping->asJson();
        return $this->post($body, $path);
    }

    /**
     * deployValueMapping
     *
     * @param  ValueMapping $valueMapping
     * @return object
     */
    public function deployValueMapping(ValueMapping $valueMapping): object
    {
        $path = '/api/v1/DeployValueMappingDesigntimeArtifact?Id=%27' . $valueMapping->Id . '%27&Version=%27' . $valueMapping->Version . '%27';
        $body = "";
        return $this->post($body, $path);
    }

    /*
     *  CONFIGURATIONS
     */

    /**
     * readConfigurations
     *
     * @param  string $artifactId
     * @param  string $version
     * @return object
     */
    public function readConfigurations(string $artifactId, string $version = "active"): object
    {
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifactId . '%27,Version=%27' . $version . '%27)/Configurations';
        return $this->get($path);
    }

    /**
     * updateConfiguration
     *
     * @param  string $artifactId
     * @param  string $version
     * @param  array $item
     * @return object
     */
    public function updateConfiguration(string $artifactId, array $item, string $version = "active") : object
    {
        $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifactId . '%27,Version=%27' . $version . '%27)/$links/Configurations(%27' . $item['ParameterKey'] . '%27)';
        $body = json_encode(array("ParameterValue" => $item['ParameterValue'], "DataType" => $item['DataType']));
        return $this->put($body, $path);
    }

    /**
     * updateConfigurations
     *
     * @param  string $artifactId
     * @param  string $version
     * @param  array $list
     * @return object
     */
    public function updateConfigurations(string $artifactId, array $list, string $version = "active") : object
    {
        foreach ($list as $item) {
            $path = '/api/v1/IntegrationDesigntimeArtifacts(Id=%27' . $artifactId . '%27,Version=%27' . $version . '%27)/$links/Configurations(%27' . $item->ParameterKey . '%27)';
            $body = json_encode(array("ParameterValue" => $item->ParameterValue, "DataType" => $item->DataType));
            $result = $this->put($body, $path);
        }
        return $result;
    }
}