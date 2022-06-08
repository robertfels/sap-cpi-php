<?php
namespace contiva\sapcpiphp;

class Package {

    public $Id;
    public $Name;
    public $Description;
    public $ShortText;
    public $Version;
    public $SupportedPlatform = "SAP Cloud Integration";
    public $Products;
    public $Keywords;
    public $Countries;
    public $Industries;
    public $LineOfBusiness;

    function __construct($Id,$Name,$ShortText) {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->ShortText = $ShortText;
    } 
    
    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function getPackageAsJson() {
        return json_encode($this);
    }

}