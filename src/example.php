<?php

require_once 'wdm/debian/Autoloader.php';

$control = new \wdm\debian\control\StandardFile();
$control
    ->setPackageName("my-package-name")
    ->setVersion("0.1.1")
    ->setDepends(array("php5", "php5-cli", "php5-xsl"))
    ->setInstalledSize(4096)
    ->setMaintainer("Walter Dal Mut", "walter.dalmut@corley.it")
    ->setProvides("my-package-name")
    ->setDescription("My software description");
;

$packager = new \wdm\debian\Packager();

$packager->setOutputPath(__DIR__ . "/out");
$packager->setControl($control);

$packager->mount(__DIR__ . "/../../diff", "/my-differ");

//Creates folders using mount points
$packager->run();

//Creates the Debian package
$packager->build();