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

    public static function withObject( object $package ) {
        $instance = new self($package->d->Id,$package->d->Name,$package->d->ShortText);
        $instance->Description = $package->d->Description;
        $instance->PackageId = $package->d->PackageId;
        $instance->Version = $package->d->Version;
        $instance->Sender = $package->d->Sender;
        $instance->Receiver = $package->d->Receiver;
        return $instance;
    }
    
    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function asJson() {
        return json_encode($this);
    }

    /**
     * enrichMetadata
     *
     * @param  object $package
     * @return void
     */
    public function enrichMetadata(object $package): void
    {
        $this->Description = $package->d->Description;
        $this->Version = $package->d->Version;
        $this->SupportedPlatform = $package->d->SupportedPlatform;
        $this->Products = $package->d->Products;
        $this->Keywords = $package->d->Keywords;
        $this->Countries = $package->d->Countries;
        $this->Industries = $package->d->Industries;
        $this->LineOfBusiness = $package->d->LineOfBusiness;
    }

}