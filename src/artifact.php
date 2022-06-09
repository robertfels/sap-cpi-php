<?php

namespace contiva\sapcpiphp;

class Artifact
{

    public string $Id;
    public string $Name;
    public string $Description;
    public string $PackageId;
    public string $Version;
    public string $Sender;
    public string $Receiver;
    public string $ArtifactContent;

    function __construct($Id, $Name)
    {
        $this->Id = $Id;
        $this->Name = $Name;
    }
    
    /**
     * enrichMetadata
     *
     * @param  object $artifact
     * @return void
     */
    public function enrichMetadata(object $artifact): void
    {
        $this->Description = $artifact->d->Description;
        $this->PackageId = $artifact->d->PackageId;
        $this->Version = $artifact->d->Version;
        $this->Sender = $artifact->d->Sender;
        $this->Receiver = $artifact->d->Receiver;
    }

    /**
     * getPackageAsJson
     *
     * @return string
     */
    public function asJson()
    {
        return json_encode($this);
    }
}
