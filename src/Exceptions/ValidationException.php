<?php

namespace Garrett9\LaravelServiceRepository\Exceptions;

/**
 * An exception thrown when given parameters were not valid.
 * 
 * @author garrettshevach@gmail.com
 *
 */
class ValidationException extends \Exception 
{
    /**
     * The validation erros associated to the exception.
     * 
     * @var array
     */
    protected $errors;
    
    /**
     * The constructor.
     * 
     * @param string $message The message to provide with the exception.
     * @param number $code The exception's code.
     * @param \Exception $previous The exception's previous exception.
     */
    public function __construct($message = 'Validation Exception!', array $errors = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct($code, $previous);
        $this->errors = $errors;
    }
    
    /**
     * Get the errors associated to the exception.
     * 
     * @return array The errors associated to the exception.
     */
    public function getErrors()
    {
        return $this->errors;
    }
}