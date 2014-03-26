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
            throw new Exception\ConfigurationException(
                'The specified basePath ' . $this->basePath . ' not exists or isn\'t a directory.'
            );
        }
    }

    public function put($path, FileDescriptor $file)
    {
        $dirName = dirname($this->createLocalPath($path));
        $isExists = file_exists($dirName);
        if ($isExists && !is_dir($dirName)) {
            throw new Exception\OperationFailureException('Path ' . dirname($path) . ' exists but it isn\'t dir');
        } elseif (!$isExists && false === mkdir($dirName, 0777, true)) {
            throw new Exception\OperationFailureException('Failed to create path ' . dirname($path));
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
