<?php
namespace wdm\debian\control;

class StandardFileTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $this->object = new StandardFile();
    }

    public function testMinimumFile()
    {
        $conf = (string)$this->object;

        $expected = <<<OEF
Version: 0.1
Section: web
Priority: optional
Architecture: all
Essential: no
Installed-Size: 1024
Maintainer: name [email]
Provides: your-company
Description: Your description

OEF;
        $this->assertEquals($expected, $conf);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFilterMissingOrInvalidProperties()
    {
        $this->object["MyPersonalSection"] = "Test";
    }
}
