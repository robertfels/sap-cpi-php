<?php
namespace contiva\sapcpiphp;

class Package {

    public $Id;
    public $Name;
    public $Description;
    public $ShortName;
    public $Version;
    public $SupportedPlatform = "SAP Cloud Integration";
    public $Products;
    public $Keywords;
    public $Countries;
    public $Industries;
    public $LineOfBusiness;

    function __construct($Id,$Name,$Description) {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->Description = $Description;
    } 

    public function getPackageAsJson() {
        return json_encode($this);
    }

}