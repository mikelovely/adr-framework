<?php

namespace Adr\Domain\Messages;

class ValidationMessage
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function toArray()
    {
        return $this->errors;
    }
}
