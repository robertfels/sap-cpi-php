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

    function __construct($Id,$Name) {
        $this->Id = $Id;
        $this->Name = $Name;
    } 
    
    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function asJson() {
        return json_encode($this);
    }

}