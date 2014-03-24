<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage\Storage\Exception;

use Exception;

class OperationFailureException extends AbstractStorageException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct('Storage operation failure. ' . $message, $code, $previous);
    }

}
