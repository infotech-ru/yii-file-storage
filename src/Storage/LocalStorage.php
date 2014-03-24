<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage\Storage;

use Infotech\FileStorage\Storage\Exception;

class LocalStorage extends AbstractStorage
{
    public $basePath;

    protected function checkConfig()
    {
        if (null === $this->basePath) {
            throw new Exception\ConfigurationException('The "basePath" parameter must be specified.');
        }
        if (is_dir($this->basePath)) {
            throw new Exception\ConfigurationException('The specified basePath not exists or isn\'t a directory.');
        }
    }

    /**
     * @param $path
     * @return string
     */
    protected function createUri($path)
    {
        return 'file://' . $this->basePath . '/'. trim($path, '/');
    }
}
