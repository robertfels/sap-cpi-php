<?php
namespace contiva\sapcpiphp;

class Configuration {

    public string $Id;
    public string $Version;
    public array $list;
    public $ArtifactContent;

    function __construct($Id,$Version) {
        $this->Id = $Id;
        $this->Version = $Version;
    } 
    
    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function asJson() {
        return json_encode($this->list);
    }

    public function setConfigurations(object $configuration){
        $this->list = (array) $configuration->d->results;
    }

}