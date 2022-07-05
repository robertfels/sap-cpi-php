<?php
namespace contiva\sapcpiphp;

use contiva\sapcpiphp\SapCpiConnection;

class SapCpiPackage extends SapCpiConnection {

    private SapCpiConnection $connection;
    public $Id;
    public $Name;
    public $Description;
    public $ShortText;
    public $Version;
    public $Vendor;
    public $PartnerContent;
    public $UpdateAvailable;
    public $mode = null;
    public $SupportedPlatform = "SAP Cloud Integration";
    public $ModifiedBy = null;
    public $CreationDate = null;
    public $ModifiedDate = null;
    public $CreatedBy = null;
    public $Products;
    public $Keywords; 
    public $Countries;
    public $Industries;
    public $LineOfBusiness;
    public $PackageContent = null;

    function __construct(SapCpiConnection $connection,$id=null) {
        $this->connection = $connection;
        $this->Id = ($id != null) ? $id : null;
    }

    public static function cast($instance, SapCpiConnection $connection, $className='contiva\sapcpiphp\Package')
    {
        $newClass = unserialize(sprintf(
            'O:%d:"%s"%s',
            \strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
        $newClass->connection = $connection;
        return $newClass;
    }

    public function listArtifacts($Id = null) : array {
        if ($Id)
        $this->Id = $Id;
        $result = $this->connection->request("GET","/IntegrationPackages('".$this->Id."')/IntegrationDesigntimeArtifacts");
        if ($result = json_decode($result->getBody())) {
            return $result->d->results;
        } else {
            return null;
        }
    }

    public function list() : array {
        $result = $this->connection->request("GET","/IntegrationPackages");
        if ($result = json_decode($result->getBody())) {
            return $result->d->results;
        } else {
            return null;
        }
    }

    public function pull ($id = null) {
        $this->Id = ($id != null) ? $id : $this->Id;
        $json = $this->connection->request("GET","/IntegrationPackages('".$this->Id."')");
        $data = json_decode($json->getBody(), true);
        foreach ($data['d'] as $key => $value) $this->{$key} = $value;
    }

    public function update () : bool  {
        $result = $this->connection->request("PUT","/IntegrationPackages('".$this->Id."')",$this->__toString());
        if ($result->getStatusCode() == 202)
        return true;
        return $result->getStatusCode();
    }

    public function create () : bool {
        $result = $this->connection->request("POST","/IntegrationPackages",$this->__toString());
        if ($result->getStatusCode() == 201)
        return true;
        return false;
    }

    public function delete () : bool {
        $result = $this->connection->request("DELETE","/IntegrationPackages('".$this->Id."')");
        return $result->getStatusCode();
        if ($result->getStatusCode() == 202)
        return true;
        return false;
    }

    public function __toString() {
        $obj = clone $this;
        unset($obj->CreationDate);
        unset($obj->CreatedBy);
        unset($obj->Mode);
        unset($obj->ModifiedBy);
        unset($obj->ModifiedDate);
        $keys = get_object_vars($obj);
        foreach ($keys as $key => $value) {
            if (!$value) {
                unset($obj->{$key});
            }
        }
        return json_encode($obj);
    }
}