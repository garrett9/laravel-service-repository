<?php 

namespace Garrett9\LaravelServiceRepository\Exceptions;

/**
 * An exception thrown whenever there is an integrity constraint with the database.
 * 
 * @author garrettshevach@gmail.com
 *
 */
class IntegrityConstraintViolationException extends \Exception
{
    /**
     * The constructor.
     * 
     * @param string $message The exception's message.
     * @param number $code The exception's code.
     * @param \Exception $previous The exception's previous exception.
     */
    public function __construct($message = 'Inegrity constrating violation!', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}