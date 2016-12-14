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

use Infotech\FileStorage\File\FileDescriptor;
use Infotech\FileStorage\Storage\Exception;

class LocalStorage extends AbstractStorage
{
    public $basePath;

    /**
     * Delete local file
     *
     * @param string  $path
     * @param boolean $deleteDir
     *
     * @return void
     */
    public function delete($path, $deleteDir = true)
    {
        // Prevent an attemp to delete nonexistent file
        if ('' !== ($path = strval($path))) {

            // Supress all warnings
            @parent::delete($path);

            // Check if dir is empty
            if (!empty($deleteDir) && 0 >= count(glob(($dir = dirname($this->createLocalPath($path))) . '/*'))) {
                @rmdir($dir);
            }
        }
    }

    protected function checkConfig()
    {
        if (null === $this->basePath) {
            throw new Exception\ConfigurationException('The "basePath" parameter must be specified.');
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
