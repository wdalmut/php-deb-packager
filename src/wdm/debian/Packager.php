<?php
namespace wdm\debian;

use wdm\debian\control\StandardFile;

class Packager
{
    private $_control;
    private $_mountPoints = array();
    private $_outputPath;

    private $_preInst;
    private $_postInst;
    private $_preRM;
    private $_postRM;

    /**
     * Set the control file
     *
     * This file contains the base package information
     *
     * @param StandardFile $control
     * @return \wdm\debian\Packager
     */
    public function setControl(StandardFile $control)
    {
        $this->_control = $control;
        return $this;
    }

    /**
     * Return the actual control file
     *
     * @return StandardFile
     */
    public function getControl()
    {
        return $this->_control;
    }

    /**
     * Pre install script
     *
     * @param string $path The absolute path of your pre-install script
     */
    public function setPreInstallScript($path)
    {
    	$this->_preInst = $path;
    }

    /**
     * Post install script
     *
     * @param string $path The absolute path of your post-install script
     */
    public function setPostInstallScript($path)
    {
    	$this->_postInst = $path;
    }

    /**
     * Pre remove script
     *
     * @param string $path The absolute path of your pre-remove script
     */
    public function setPreRemoveScript($path)
    {
    	$this->_preRM = $path;
    }

    /**
     * Post remove script
     *
     * @param string $path The absolute path of your post-remove script
     */
    public function setPostRemoveScript($path)
    {
    	$this->_postRM = $path;
    }

    public function mount($sourcePath, $destinationPath)
    {
        $this->_mountPoints[$sourcePath] = $destinationPath;
        return $this;
    }

    public function setOutputPath($path)
    {
        $this->_outputPath = $path;
        return $this;
    }

    public function run()
    {
        if (file_exists($this->_outputPath)) {
            $iterator = new \DirectoryIterator($this->_outputPath);
            foreach ($iterator as $path) {
                if ($path != '.' || $path != '..') {
                    echo "OUTPUT DIRECTORY MUST BE EMPTY! Something exists, exit immediately!" . PHP_EOL;
                    exit();
                }
            }
        }

        mkdir($this->_outputPath, 0777);

        foreach ($this->_mountPoints as $path => $dest) {
            $this->_pathToPath($path, $this->_outputPath . DIRECTORY_SEPARATOR . $dest);
        }

        mkdir($this->_outputPath . "/DEBIAN", 0777);

        file_put_contents($this->_outputPath . "/DEBIAN/control", (string)$this->_control);

        if ($this->_preInst) {
        	$dest = $this->_outputPath . "/DEBIAN/preinst";
        	$this->_copy($this->_preInst, $dest);
        	chmod($dest, 0755);
        }

        if ($this->_postInst) {
        	$dest = $this->_outputPath . "/DEBIAN/postinst";
        	$this->_copy($this->_postInst, $dest);
        	chmod($dest, 0755);
        }

        if ($this->_preRM) {
        	$dest = $this->_outputPath . "/DEBIAN/prerm";
        	$this->_copy($this->_preRM, $dest);
        	chmod($dest, 0755);
        }

        if ($this->_postRM) {
        	$dest = $this->_outputPath . "/DEBIAN/postrm";
        	$this->_copy($this->_postRM, $dest);
        	chmod($dest, 0755);
        }

        return $this;
    }

    private function _pathToPath($path, $dest)
    {
        if (is_dir($path)) {
            $iterator = new \DirectoryIterator($path);
            foreach ($iterator as $element) {
                if ($element != '.' && $element != '..') {
                    $fullPath = $path . DIRECTORY_SEPARATOR . $element;
                    if (is_dir($fullPath)) {
                        $this->_pathToPath($fullPath, $dest . DIRECTORY_SEPARATOR . $element);
                    } else {
                        $this->_copy($fullPath, $dest . DIRECTORY_SEPARATOR . $element);
                    }
                }
            }
        } else if (is_file($path)) {
            $this->_copy($path, $dest);
        }
    }

    private function _copy($source, $dest)
    {
        $destFolder = dirname($dest);
        if (!file_exists($destFolder)) {
            mkdir($destFolder, 0777, true);
        }
        copy($source, $dest);
    }

    public function build($debPackageName = false)
    {
        if (!$debPackageName) {
            $debPackageName = basename($this->_outputPath . ".deb");
        }

        $command = "dpkg -b {$this->_outputPath} {$debPackageName}";

        return $command;
    }
}
