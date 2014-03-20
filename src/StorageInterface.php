<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage;

/**
 * File Storage Interface
 */
interface StorageInterface
{

    /**
     * Fetch file from storage
     *
     * @param string $path
     * @return FileDescriptor
     */
    public function get($path);

    /**
     * Put file into storage
     *
     * @param string $path
     * @param FileDescriptor $file
     * @throws StorageException if storage failure
     */
    public function put($path, FileDescriptor $file);

    /**
     * Delete file from storage
     *
     * @param string $path
     * @throws StorageException if storage failure
     */
    public function delete($path);

    /**
     * @param string $path
     * @return boolean
     */
    public function exists($path);
} 
