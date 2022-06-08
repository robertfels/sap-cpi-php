<?php
namespace contiva\sapcpiphp;

class ValueMapping {

    public string $Id;
    public string $Name;
    public string $Description;
    public string $PackageId;
    public string $Version;
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