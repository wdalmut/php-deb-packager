<?php
namespace wdm\debian;

class PackagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->object = new Packager();
    }

    public function testRetrieveTheBuildCommand()
    {
        $this->object->setOutputPath("/tmp");

        $this->assertEquals("dpkg -b /tmp my.deb", $this->object->build("my.deb"));
    }

    public function testRetriveControlFile()
    {
        $control = new control\StandardFile();
        $this->object->setControl($control);

        $this->assertSame($control, $this->object->getControl());
    }
}
