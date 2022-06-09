<?php

namespace contiva\sapcpiphp;

class Configuration
{

    private string $Id;
    private string $Version;
    private array $list;
    
    /**
     * __construct
     *
     * @param  string $Id
     * @param  string $Version
     * @return void
     */
    function __construct($Id, $Version)
    {
        $this->Id = $Id;
        $this->Version = $Version;
    }

    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function asJson()
    {
        return json_encode($this->list);
    }
    
    /**
     * setConfigurations
     *
     * @param  object $configuration
     * @return void
     */
    public function setConfigurations(object $configuration) : void
    {
        $this->list = (array) $configuration->d->results;
    }
    
    /**
     * getList
     *
     * @return array
     */
    public function getList() : array
    {
        return $this->list;
    }
    
    /**
     * changeConfiguration
     *
     * @param  string $paramName
     * @param  string $paramValue
     * @return bool
     */
    public function changeConfiguration(string $paramKey, string $paramValue) : bool
    {
        foreach ($this->list as $item) {
            if ($item->ParameterKey == $paramKey) {
                $item->ParameterValue = $paramValue;
                return true;
            }
        }
        return false;
    }
}
