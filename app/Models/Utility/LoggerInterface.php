<?php
/**
 * Interface | app/Models/Utility/LoggerInterface.php
 * Interface for non-static loggers
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Utility;

interface LoggerInterface
{
    public function debug($message, $data);

    public function info($message, $data);

    public function warning($message, $data);

    public function error($message, $data);
}