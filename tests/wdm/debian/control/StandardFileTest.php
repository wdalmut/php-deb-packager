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
Maintainer: name <email>
Provides: your-company
Description: Your description

OEF;
        $this->assertEquals($expected, $conf);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid property 'MyPersonalSection' for this control file.
     */
    public function testFilterMissingOrInvalidProperties()
    {
        $this->object["MyPersonalSection"] = "Test";
    }

    public function testOverwriteConfiguration()
    {
        $this->object["Version"] = "1.0.1";
        $this->object["Section"] = "Software";
        $this->object["Priority"] = "security";
        $this->object["Architecture"] = "x86";
        $this->object["Essential"] = "yes";
        $this->object["Installed-Size"] = "2048";
        $this->object["Maintainer"] = "Walter Dal Mut <walter.dalmut at gmail dot com>";
        $this->object["Provides"] = "Corley SRL";
        $this->object["Description"] = "My Desc";
        $this->object["Depends"] = "php5-cli";
        $this->object["Recommends"] = "php5-curl";

        $conf = (string)$this->object;

        $expected = <<<OEF
Version: 1.0.1
Section: Software
Priority: security
Architecture: x86
Essential: yes
Depends: php5-cli
Recommends: php5-curl
Installed-Size: 2048
Maintainer: Walter Dal Mut <walter.dalmut at gmail dot com>
Provides: Corley SRL
Description: My Desc

OEF;
        $this->assertEquals($expected, $conf);
    }
}
