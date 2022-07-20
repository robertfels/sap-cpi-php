<?php
namespace contiva\sapcpiphp;

use RuntimeException;
use contiva\sapcpiphp\SapCpiConnection;
use GuzzleHttp\Exception\ClientException;

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

    function __construct(SapCpiConnection $connection,string $Id = null,string $Name = null,string $ShortText = null) {
        $this->connection = $connection;
        $this->Id = ($Id != null) ? $Id : null;
        $this->Name = ($Name != null) ? $Name : null;
        $this->ShortText = ($ShortText != null) ? $ShortText : null;
    }

    public function changeConnection(SapCpiConnection $connection) : SapCpiPackage {
        $this->connection = $connection;
        return $this; 
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
            return $this->sort($result->d->results);
        } else {
            return null;
        }
    }
    
    /**
     * pull
     *
     * @param  string $id
     * @param  bool $response
     * @return SapCpiPackage
     */
    public function pull ($id = null,bool $response = true) : SapCpiPackage{
        try {
            $this->Id = ($id != null) ? $id : $this->Id;
            $json = $this->connection->request("GET","/IntegrationPackages('".$this->Id."')");
            $data = json_decode($json->getBody(), true);
            foreach ($data['d'] as $key => $value) {
                if ($key != "__metadata" && $key != "__deferred")
                $this->{$key} = $value;
            } 
        } catch (ClientException $e) {
            if (($e->getResponse()->getStatusCode() != 404) && ($response == false)) {
                throw $e;
            }
        }
        return $this;
    }

    public function update () : bool  {
        $result = $this->connection->request("PUT","/IntegrationPackages('".$this->Id."')",$this->__toString());
        if ($result->getStatusCode() == 202)
        return true;
        return $result->getStatusCode();
    }

    public function create (string $Name = null, string $ShortText = null) : bool {
        try {
            //Declare manuals
            if ($Name) $this->Name = $Name;
            if ($ShortText) $this->ShortText = $ShortText;

            //Exceptions
            if ($this->Name == null) throw new RuntimeException('Name is required for package creation');
            if ($this->Id == null) throw new RuntimeException('ShortText is required for package creation');
            if ($this->ShortText == null) throw new RuntimeException('ShortText is required for package creation');

            //Do it
            $result = $this->connection->request("POST","/IntegrationPackages",$this->__toString());
            if ($result->getStatusCode() == 201)
            return true;
            return false;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 409) {
                return false;
            } else if ($e->getResponse()->getStatusCode() == 500) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function delete () : bool {
        try {
            $result = $this->connection->request("DELETE","/IntegrationPackages('".$this->Id."')");
            if ($result->getStatusCode() == 202)
            return true;
            return false;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                return false;
            } else {
                throw $e;
            }
        }
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

    private function sort($input) {
        $input = json_decode(json_encode($input), true);
        array_multisort( array_column($input, "Name"), SORT_ASC, $input );
        $input = json_decode(json_encode($input));
        return $input;
    }
}