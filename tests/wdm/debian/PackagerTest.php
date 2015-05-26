<?php
namespace wdm\debian;

use org\bovigo\vfs\vfsStream;

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

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage OUTPUT DIRECTORY MUST BE EMPTY! Something exists, exit immediately!
     */
    public function testOutputFolderIsNotEmpty()
    {
        $root = vfsStream::setup('root');

        mkdir(vfsStream::url('root/tmp'));
        file_put_contents(vfsStream::url('root/tmp/ciao.txt'), "ciao");

        $this->object->setOutputPath(vfsStream::url('root/tmp'));

        $control = new control\StandardFile();
        $this->object->setControl($control);

        $this->object->run();
    }

    public function testOutputFolderExistsButIsEmpty()
    {
        $root = vfsStream::setup('root');

        mkdir(vfsStream::url('root/tmp'));

        $this->object->setOutputPath(vfsStream::url('root/tmp'));

        $control = new control\StandardFile();
        $this->object->setControl($control);

        $this->object->run();
        $command = $this->object->build();

        $this->assertEquals("dpkg -b vfs://root/tmp tmp.deb", $command);
    }

    public function testCreateDebWhenOutputFolderIsMissing()
    {
        $root = vfsStream::setup('root');

        $this->object->setOutputPath(vfsStream::url('root/tmp'));

        $control = new control\StandardFile();
        $this->object->setControl($control);

        $this->object->run();
        $command = $this->object->build();

        $this->assertEquals("dpkg -b vfs://root/tmp tmp.deb", $command);
    }

    public function testCreateDebPackageWithAnotherName()
    {
        $root = vfsStream::setup('root');

        $this->object->setOutputPath(vfsStream::url('root/tmp'));

        $control = new control\StandardFile();
        $this->object->setControl($control);

        $this->object->run();
        $command = $this->object->build("myname.deb");

        $this->assertEquals("dpkg -b vfs://root/tmp myname.deb", $command);
    }
}
