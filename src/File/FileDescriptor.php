<?php
/*
 * This file is part of the infotech/yii-file-storage package.
 *
 * (c) Infotech, Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infotech\FileStorage\File;

use Infotech\FileStorage\File\Exception\FileDescriptorCreationException;

/**
 * File descriptor
 *
 * Stores file's metadata and wraps some filename operations.
 *
 */
class FileDescriptor
{
    const SCHEME_RESOURCE = 'resource';
    const SCHEME_LOCAL = 'file';

    const MIME_TYPE_DEFAULT = 'application/octet-stream';

    /**
     * @var string
     */
    private $uri;

    /**
     * @var resource
     */
    private $stream;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var integer
     */
    private $size;

    /**
     * @param string|resource $uriOrResource    File URI ({@link http://php.net/manual/en/wrappers.php})
     *                                          or stream resource ({@link fopen()})
     * @param integer         $size             File size in bytes.
     * @param string          $mimeType         Optional. File data MIME type.
     * @throws FileDescriptorCreationException if $uriOrResourse isn't neither string, nor resource
     * @throws FileDescriptorCreationException if uri is empty string
     * @throws FileDescriptorCreationException if file size is less then zero
     */
    public function __construct($uriOrResource, $size, $mimeType = self::MIME_TYPE_DEFAULT)
    {
        if (!$uriOrResource || $size < 0) {
            throw new FileDescriptorCreationException('Empty URI or Resource specified');
        }

        if (is_resource($uriOrResource)) {
            $this->stream = $uriOrResource;
            $this->uri = strtr(uniqid(self::SCHEME_RESOURCE . '://', true), '.', '/');
        } elseif (is_string($uriOrResource)) {
            $hasScheme = strpos($uriOrResource, '://') !== false;
            $this->uri = ($hasScheme ? '' : self::SCHEME_LOCAL . '://') . $uriOrResource;
        } else {
            throw new FileDescriptorCreationException('Unknown type');
        }

        $this->mime = $mimeType;
        $this->size = $size;
    }

    /**
     * Get basename of file
     *
     * @return string
     */
    public function getBasename()
    {
        return basename($this->uri);
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getPath()
    {
        return substr($this->uri, strpos($this->uri, '://') + 3);
    }

    /**
     * Get directory name of file
     *
     * @return string
     */
    public function getDirname()
    {
        return dirname($this->getPath());
    }

    /**
     * Get filename extension
     *
     * @return string
     */
    public function getExtension()
    {
        $basename = $this->getBasename();
        if (false !== $pos = strrpos($basename, '.')) {
            $ext = substr($basename, $pos + 1);
        } else {
            $ext = '';
        }
        return $ext;
    }

    /**
     * Get file scheme (protocol)
     *
     * @return string
     */
    public function getScheme()
    {
        return substr($this->uri, 0, strpos($this->uri, '://'));
    }

    /**
     * Get internal file uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get a handler resource for reading
     *
     * @see fopen()
     *
     * @return resource
     */
    public function getReadHandler()
    {
        if (null === $this->stream) {
            $this->stream = fopen($this->uri, 'r');
        }
        return $this->stream;
    }

    /**
     * Get file contents as binary string
     *
     * @return string
     */
    public function getContents()
    {
        if (self::SCHEME_RESOURCE === $this->getScheme()) {
            $contents = '';
            while ($data = fread($this->stream, 2048)) {
                $contents .= $data;
            }
        } else {
            $contents = file_get_contents($this->uri);
        }
        return $contents;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

} 
