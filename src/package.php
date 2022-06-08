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

    function __construct($Id,$Name,$ShortName) {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->ShortName = $ShortName;
    } 

    public function getPackage() {
        return json_encode($this);
    }

}