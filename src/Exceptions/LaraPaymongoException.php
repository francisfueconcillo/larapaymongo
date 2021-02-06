<?php

namespace PepperTech\LaraPaymongo\Exceptions;

class LaraPaymongoException extends \UnexpectedValueException
{
  public function __construct(string $message)
  {
    $this->message = "[LaraPaymongo] {$message}";
  }
}