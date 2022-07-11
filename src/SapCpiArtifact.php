<?php

namespace contiva\sapcpiphp;

use stdClass;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;

class SapCpiArtifact extends SapCpiConnection
{

    private SapCpiConnection $connection;
    public $Id;
    public $Name;
    public $Description;
    public $PackageId; 
    public $Version = "active";
    public $Sender;
    public $Receiver;
    public $ArtifactContent = null;

    function __construct(SapCpiConnection $connection,$id=null) {
        $this->connection = $connection;
        $this->Id = ($id != null) ? $id : null;
    }

    public function push(stdClass $instance)
    {
        foreach($instance as $k => $v) $this->$k = $v;
    }

    public function pull ($id = null,bool $response = true) : SapCpiArtifact {
        try {
            $this->Id = ($id != null) ? $id : $this->Id;
            $json = $this->connection->request("GET","/IntegrationDesigntimeArtifacts(Id='".$this->Id."',Version='active')");
            $data = json_decode($json->getBody(), true);
            foreach ($data['d'] as $key => $value) $this->{$key} = $value;
        } catch (ClientException $e) {
            if (($e->getResponse()->getStatusCode() != 404) && ($response == false)) {
                throw $e;
            }
        }
        return $this;
    }
    
    public function pullContent() : bool
    {
        $result = $this->connection->request("GET","/IntegrationDesigntimeArtifacts(Id='" . $this->Id . "',Version='" . $this->Version . "')/\$value");
        if ($result->getBody())
        $this->ArtifactContent = base64_encode($result->getBody());
        return true;
    }

    public function pullConfiguration() : bool
    {
        $json = $this->connection->request("GET","/IntegrationDesigntimeArtifacts(Id='" . $this->Id . "',Version='" . $this->Version . "')/Configurations");
        $data = json_decode($json->getBody());
        $this->Configuration = $data->d->results;
        return true;
    }

    public function list() : array {
        $result = $this->connection->request("GET","/IntegrationDesigntimeArtifacts");
        if ($result = json_decode($result->getBody())) {
            return $this->sort($result->d->results);
        } else {
            return null;
        }
    }

    public function delete() : bool {
        try {
            $result = $this->connection->request("DELETE","/IntegrationDesigntimeArtifacts(Id='".$this->Id."',Version='".$this->Version."')");  
            if ($result->getStatusCode() == 202)
            return true;
            return false;
        }
        catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function update () : bool  {
        $result = $this->connection->request("PUT","/IntegrationDesigntimeArtifacts(Id='".$this->Id."',Version='".$this->Version."')",$this->__toString());
        if ($result->getStatusCode() == 202)
        return true;
        return $result->getStatusCode();
    }

    public function create () : bool {
        try {
            $this->version = null;
            $result = $this->connection->request("POST","/IntegrationDesigntimeArtifacts",$this->__toString());
            if ($result->getStatusCode() == 201)
            return true;
            return false;
        }
        catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() == 409) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function deploy() : bool
    {
        try {
            $result = $this->connection->request("DELETE","/DeployIntegrationDesigntimeArtifact?Id='" . $this->Id . "'&Version='" . $this->Version . "'");
            return $result->getStatusCode();
            if ($result->getStatusCode() == 202)
            return true;
            return false;
        } catch (ClientException $e) {
            throw $e;
        }
    }

    public function getConfiguration()
    {
        return $this->Configuration;
    }

    public function changeConfiguration($key, $value)
    {
        $i = 0;
        foreach ($this->Configuration as $val) {
            if ($val->ParameterKey == $key) {
                $this->Configuration[$i]->ParameterValue = $value;
            }
            $i++;
        }
    }

    public function __toString() {
        $obj = clone $this;
        unset($obj->CreationDate);
        unset($obj->CreatedBy);
        unset($obj->Mode);
        unset($obj->Version);
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
