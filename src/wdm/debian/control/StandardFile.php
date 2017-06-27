<?php
namespace wdm\debian\control;

/**
 *
 * Representation of Standard control file for Debian packages
 *
 * @author Walter Dal Mut
 * @package
 * @license MIT
 *
 * Copyright (C) 2012 Corley S.R.L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class StandardFile
    implements \ArrayAccess
{
    private $_keys = array(
        'Package' => false,
        'Version' => '0.1',
        "Section" => "web",
        "Priority" => "optional",
        "Architecture" => "all",
        "Essential" => "no",
        "Depends" => false,
        "Pre-Depends" => false,
        "Recommends" => false,
        "Suggests" => false,
        "Installed-Size" => 1024,
        "Maintainer" => "name [email]",
        "Conflicts" => false,
        "Replaces" => false,
        "Provides" => "your-company",
        "Description" => "Your description"
    );

    public function setPackageName($name)
    {
        return $this->_setProperty("Package", $name);
    }

    public function setVersion($version)
    {
        return $this->_setProperty("Version", $version);
    }

    public function setSection($section)
    {
        return $this->_setProperty("Section", $section);
    }

    public function setPriority($priority)
    {
        return $this->_setProperty($this["Priority"], $priority);
    }

    public function setArchitecture($arch)
    {
        return $this->_setProperty("Architecture", $arch);
    }

    public function setEssential($essential)
    {
        return $this->_setProperty("Essential", $essential);
    }

    public function setDepends($depends)
    {
        return $this->_setProperty("Depends", $this->_transformList($depends));
    }

    public function setPreDepends($depends)
    {
        return $this->_setProperty("Pre-Depends", $this->_transformList($depends));
    }

    public function setRecommends($depends)
    {
        return $this->_setProperty("Reccommends", $depends);
    }

    public function setSuggests($depends)
    {
        return $this->_setProperty("Suggests", $this->_transformList($depends));
    }

    public function setInstalledSize($size)
    {
        return $this->_setProperty("Installed-Size", $size);
    }

    public function setMaintainer($maintainer, $email = false)
    {
        $email = ($email) ? $email : "---";
        return $this->_setProperty("Maintainer", $maintainer . "[{$email}]");
    }

    public function setConflicts($conflicts)
    {
        return $this->_setProperty("Conflicts", $this->_transformList($conflicts));
    }

    public function setReplaces($replaces)
    {
        return $this->_setProperty("Conflicts", $this->_transformList($replaces));
    }

    public function setProvides($provides)
    {
        return $this->_setProperty("Provides", $provides);
    }

    public function setDescription($description)
    {
        return $this->_setProperty("Description", $description);
    }

    private function _transformList($depends)
    {
        if (is_array($depends)) {
            $depends = implode(", ", $depends);
        } else {
            $depends = $depends;
        }

        return $depends;
    }

    private function _setProperty($key, $value)
    {
        $this[$key] = $value;
        return $this;
    }

    public function offsetExists ($offset)
    {
        return array_key_exists($offset, $this->_keys);
    }

    public function offsetGet ($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_keys[$offset];
        } else {
            return null;
        }
    }

    public function offsetSet ($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new \InvalidArgumentException("Invalid property '{$offset}' for this control file.");
        }
        $this->_keys[$offset] = $value;
    }

    public function offsetUnset ($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_keys[$offset]);
        }
    }

    /**
     * Control file string representation.
     *
     * @return string The control file
     */
    public function __toString()
    {
        $control = '';
        foreach ($this->_keys as $key => $value) {
            if ($value) {
                $control .= "{$key}: {$value}" . PHP_EOL;
            }
        }

        return $control;
    }
}
