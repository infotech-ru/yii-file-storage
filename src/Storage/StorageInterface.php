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

/**
 * File Storage Interface
 */
interface StorageInterface
{

    /**
     * Fetch file from storage
     *
     * @param string $path
     * @return FileDescriptor|null File Descriptor or null if file not found
     * @throws Exception\OperationFailureException if storage failure
     */
    public function get($path);

    /**
     * Put file into storage
     *
     * @param string $path
     * @param FileDescriptor $file
     * @throws Exception\OperationFailureException if storage failure
     */
    public function put($path, FileDescriptor $file);

    /**
     * Delete file from storage
     *
     * @param string $path
     * @throws Exception\OperationFailureException if storage failure
     */
    public function delete($path);

    /**
     * @param string $path
     * @return boolean
     */
    public function exists($path);
} 
