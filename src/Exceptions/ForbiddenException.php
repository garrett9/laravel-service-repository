<?php 

namespace Garrett9\LaravelServiceRepository\Exceptions;

/**
 * An exception thrown whenever a certain action is not allowed.
 * 
 * @author garrettshevach@gmail.com
 *
 */
class ForbiddenException extends \Exception
{
    /**
     * The constructor.
     * 
     * @param string $message The exception's message.
     * @param number $code The exception's code.
     * @param \Exception $previous The exception's previous exception.
     */
    public function __construct($message = 'Forbidden!', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}