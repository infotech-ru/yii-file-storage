<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage\Storage\Exception;

use Exception;

class ConfigurationException extends AbstractStorageException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct('Error of Local Storage configuration. ' . $message, $code, $previous);
    }

}
