<?php
/*
 * This file is part of the infotech/yii-file-storage package.
 *
 * (c) Infotech, Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infotech\FileStorage\Storage;


use CApplicationComponent;
use Infotech\FileStorage\File\FileDescriptor;

abstract class AbstractStorage extends CApplicationComponent implements StorageInterface
{
    public function init()
    {
        parent::init();
        $this->checkConfig();
    }

    public function putPath($path, $localPath)
    {
        $this->put($path, new FileDescriptor($localPath, filesize($localPath)));
    }

    public function putStream($path, $resource, $size)
    {
        $this->put($path, new FileDescriptor($resource, $size));
    }

    public function delete($path)
    {
        unlink($this->createUri($path));
    }

    public function exists($path)
    {
        return file_exists($this->createUri($path));
    }

    /**
     * Fetch file from storage
     *
     * @param string $path
     * @return \Infotech\FileStorage\File\FileDescriptor|null
     * @throws \Infotech\FileStorage\Storage\Exception\OperationFailureException if storage failure
     */
    public function get($path)
    {
        $localPath = $this->createUri($path);
        if (!file_exists($localPath)) {
            return null;
        } elseif (!is_readable($localPath)) {
            throw new Exception\OperationFailureException('File reading error.');
        } else {
            return new FileDescriptor($localPath, filesize($localPath));
        }
    }

    /**
     * Put file into storage
     *
     * @param string $path
     * @param FileDescriptor $file
     * @throws \Infotech\FileStorage\Storage\Exception\OperationFailureException if storage failure
     */
    public function put($path, FileDescriptor $file)
    {
        $localPath = $this->createUri($path);
        if (false === copy($file->getUri(), $localPath)) {
            throw new Exception\OperationFailureException('Can\'t create file ' . $path . '.');
        }
    }

    /**
     * Get storage internal URI
     *
     * @param string $path  Path to file
     * @return string
     */
    abstract protected function createUri($path);

    /**
     * Check component configuration
     *
     * @throw \Infotech\FileStorage\Storage\Exception\ConfigurationException
     */
    abstract protected function checkConfig();

    /**
     * Coping one stream to another
     *
     * @param resource $source Input stream resource
     * @param resource $destination Output stream resource
     * @return integer Count of bytes copied
     */
    protected static function pipe($source, $destination)
    {
        return stream_copy_to_stream($source, $destination);
    }

}
