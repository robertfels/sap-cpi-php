<?php
namespace contiva\sapcpiphp;

class Artifact {

    public string $Id;
    public string $Name;
    public string $Description;
    public string $PackageId;
    public string $Version;
    public string $Sender;
    public string $Receiver;
    public $ArtifactContent;
    public $Configurations;
    public $Ressources;

    function __construct($Id,$Name) {
        $this->Id = $Id;
        $this->Name = $Name;
    } 
    
    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function getArtifactAsJson() {
        return json_encode($this);
    }

}