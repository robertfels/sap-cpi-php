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
use Webmozart\Assert\Assert;

class Employee
{
    public function __construct($id)
    {
        Assert::integer($id, 'The employee ID must be an integer. Got: %s');
        Assert::greaterThan($id, 0, 'The employee ID must be a positive integer. Got: %s');
    }
}
```
