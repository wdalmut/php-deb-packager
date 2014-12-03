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
}
