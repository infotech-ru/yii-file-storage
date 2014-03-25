<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage\Storage;

use Infotech\FileStorage\File\FileDescriptor;
use Infotech\FileStorage\Storage\Exception;

class LocalStorage extends AbstractStorage
{
    public $basePath;

    protected function checkConfig()
    {
        if (null === $this->basePath) {
            throw new Exception\ConfigurationException('The "basePath" parameter must be specified.');
        }
        if (!is_dir($this->basePath)) {
            throw new Exception\ConfigurationException('The specified basePath not exists or isn\'t a directory.');
        }
    }

    public function put($path, FileDescriptor $file)
    {
        if (false === mkdir(dirname($this->createLocalPath($path)), 0777, true)) {
            throw new Exception\OperationFailureException('Path creation failure');
        }
        parent::put($path, $file);
    }


    /**
     * @param string $path
     * @return string
     */
    protected function createUri($path)
    {
        return 'file://' . $this->createLocalPath($path);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function createLocalPath($path)
    {
        return $this->basePath . '/' . trim($path, '/');
    }
}
