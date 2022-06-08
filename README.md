sap-cpi-php
===============
[![Latest Stable Version](https://poser.pugx.org/contiva/sap-cpi-php/v/stable.svg)](https://packagist.org/packages/webmozart/assert)
[![Total Downloads](https://poser.pugx.org/contiva/sap-cpi-php/downloads.svg)](https://packagist.org/packages/webmozart/assert)

With this library you can easily access the standard SAP API's for your SAP Cloud Platform Integration.
https://api.sap.com/api/IntegrationContent/resource

Installation
------------

Use [Composer] to install the package:

```bash
composer require contiva/sap-cpi-php
```

Example
-------

```php
require 'vendor/autoload.php';

use contiva\sapcpiphp\Package;
use contiva\sapcpiphp\SapCpiHelper;

//Set Credentials
$hostname = "yourtenant.it-cpi005.cfapps.eu20.hana.ondemand.com";
$username = "user.name@muster.com";
$password = "youSecretP4ssWord";

//Init Package
$cpihelper = new SapCpiHelper($hostname,$username,$password);

//Authentication
echo "Authentication...".PHP_EOL;
$auth = $cpihelper->auth();
if ($auth->status == "success") {
    echo "success".PHP_EOL;
} else {
    echo $auth->message.PHP_EOL;
}

echo PHP_EOL;

//Packages lesen
echo "Packages read...".PHP_EOL;
$result = $cpihelper->readPackages();
if ($result->d) {
    foreach ($result->d->results as $item) {
        echo $item->Name.' ('.$item->Id.')'.PHP_EOL;
    }
    $lastOfUs = $result->d->results[9]->Id;
} else {
    echo $result->message->error->message->value.PHP_EOL;
}

echo PHP_EOL;

//Artifacts lesen
echo "Artifacts read...".PHP_EOL;
$result = $cpihelper->readFlowsOfPackage($lastOfUs);
if (isset($result->d)) {
    foreach ($result->d->results as $item) {
        echo $item->Name.' ('.$item->Id.')'.PHP_EOL;
    }
} else {
    echo $result->message->error->message->value.PHP_EOL;
}

echo PHP_EOL;

//Value Mappings lesen
echo "Value Mappings from Package read...".PHP_EOL;
$result = $cpihelper->readValueMapsOfPackage($lastOfUs);
if (isset($result->d)) {
    foreach ($result->d->results as $item) {
        echo $item->Name.' ('.$item->Id.')'.PHP_EOL;
    }
} else {
    echo $result->message->error->message->value.PHP_EOL;
}

echo PHP_EOL;

//Value Mappings read
echo "Value Mappings read...".PHP_EOL;
$result = $cpihelper->readValueMappings();
if (isset($result->d)) {
    foreach ($result->d->results as $item) {
        echo $item->Name.' ('.$item->Id.')'.PHP_EOL;
    }
} else {
    echo $result->message->error->message->value.PHP_EOL;
}

echo PHP_EOL;
```
