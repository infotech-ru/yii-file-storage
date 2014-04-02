<?php
/*
 * This file is part of the infotech/yii-file-storage package.
 *
 * (c) Infotech, Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
