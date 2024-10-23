<?php

namespace fastphp;

class GetResponseForExceptionEvent extends GetResponseEvent
{
  protected $exception;

  public function __construct(\Exception $exception, Request $request)
  {
    $this->exception = $exception;
    
    parent::__construct($request);
  }

  public function getException(): \Exception
  {
    return $this->exception;
  }
}
