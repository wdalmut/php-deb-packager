# Debian packager 4 PHP

A simple debian packager for PHP applications

```php
<?php

require_once 'wdm/debian/Autoloader.php';

$control = new \wdm\debian\control\StandardFile();
$control
    ->setPackage("my-package-name")
    ->setVersion("0.1.1")
    ->setDepends(array("php5", "php5-cli", "php5-xsl"))
    ->setInstalledSize(4096)
    ->setMaintainer("Walter Dal Mut", "walter.dalmut@corley.it")
    ->setProvides("Corley S.r.l.")
    ->setDescription("My software description");
;

$packager = new \wdm\debian\Packager();

$packager->setOutputPath("/path/to/out");
$packager->setControl($control);

$packager->mount("/path/to/source-conf", "/etc/my-sw");
$packager->mount("/path/to/exec", "/usr/bin/my-sw");
$packager->mount("/path/to/docs", "/usr/share/docs");

//Creates folders using mount points
$packager->run();

//Creates the Debian package
$packager->build();
```