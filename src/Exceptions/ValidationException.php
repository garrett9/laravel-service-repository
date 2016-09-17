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
    public function __construct($message = 'Validation Exception!', array $errors = [])
    {
        parent::__construct($message);
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
    
    /**
     * Returns the exceptions errors as a JSON encoded string.
     *
     * @param string $pretty_print True for pretty printing; False otherwise (default is false).
     * @return string The JSON representation of the errors.
     */
    public function getErrorsInJson($pretty_print=false)
    {
        if($pretty_print)
            return json_encode($this->errors, JSON_PRETTY_PRINT);
            return json_encode($this->errors);
    }
    
    /**
     * (non-PHPdoc)
     * @see Exception::__toString()
     */
    public function __toString()
    {
        $str = __CLASS__ . ': ' . $this->message;
        if($this->errors)
            $str .= "\n" . $this->getErrorsInJson(true);
            return $str;
    }
}