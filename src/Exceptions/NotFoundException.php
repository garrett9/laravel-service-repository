<?php
namespace Garrett9\LaravelServiceRepository\Exceptions;

/**
 * An exception to indicate the a record was not found.
 *
 * @author garrettshevach@gmail.com
 *        
 */
class NotFoundException extends \Exception
{
    /**
     * The constructor.
     *
     * @param string $message
     *            The messageo the exception.
     * @param number $code
     *            The code of the exception.
     * @param \Exception $previous
     *            The exception's previous exception.
     */
    public function __construct($message = 'The record you were looking for was not found!', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}