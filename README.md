# Debian packager (PHP)

 * Develop: [![Build Status](https://travis-ci.org/wdalmut/php-deb-packager.svg?branch=develop)](https://travis-ci.org/wdalmut/php-deb-packager)
 * Master : [![Build Status](https://travis-ci.org/wdalmut/php-deb-packager.svg?branch=master)](https://travis-ci.org/wdalmut/php-deb-packager)

A simple debian packager for PHP applications

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

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

$packager->setOutputPath("/path/to/out");
$packager->setControl($control);

$packager->mount("/path/to/source-conf", "/etc/my-sw");
$packager->mount("/path/to/exec", "/usr/bin/my-sw");
$packager->mount("/path/to/docs", "/usr/share/docs");

//Creates folders using mount points
$packager->run();

//Get the Debian package command
echo $packager->build();
```

**Create the Package**

```
$(php pack.php)
```

## Pre-Post scripts

Optianally you can add script for different hooks

 * pre-install
   * Run pre install
 * post-install
   * Run post install
 * pre-remove
   * Run pre package remove
 * post-remove
   * Run post package remove

Adding scripts

```php
$packager->setPreInstallScript(__DIR__ . '/my-pre-install-script.sh');
$packager->setPostInstallScript(__DIR__ . '/my-post-install-script.sh');
$packager->setPreRemoveScript(__DIR__ . '/my-pre-remove-script.sh');
$packager->setPreRemoveScript(__DIR__ . '/my-post-remove-script.sh');
```

See a script example

```shell
#!/bin/sh
#postinst script for upcloo

set -e

echo "Goodbye Cruel World"

exit 0
```

