<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage;

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

    /**
     * @var string
     */
    private $uri;

    /**
     * @var resource
     */
    private $stream;

    /**
     * @param string|resource $uriOrResource    File URI ({@link http://php.net/manual/en/wrappers.php})
     *                                          or stream resource ({@link fopen()})
     * @param string          $mimeType         Optional. File data MIME type.
     */
    public function __construct($uriOrResource, $mimeType = null)
    {
        if (is_resource($uriOrResource)) {
            $this->stream = $uriOrResource;
            $this->uri = strtr(uniqid(self::SCHEME_RESOURCE . '://', true), '.', '/');
        } else {
            $hasScheme = strpos($uriOrResource, '://') !== false;
            $this->uri = ($hasScheme ? '' : self::SCHEME_LOCAL . '://') . $uriOrResource;
        }
        $this->mime = $mimeType;
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
     * Get directory name of file
     *
     * @return string
     */
    public function getDirname()
    {
        return dirname(substr($this->uri, strpos($this->uri, '://') + 3));
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

} 
