<?php

namespace OptiGov\Exceptions;

use Throwable;

class GraphQLException extends OptiGovException
{
    /**
     * @var array
     */
    private array $errors;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return GraphQLException
     */
    public function setErrors(array $errors): GraphQLException
    {
        $this->errors = $errors;
        return $this;
    }
}