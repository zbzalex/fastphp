<?php

namespace fastphp;

class BaseEvent extends Event
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function getRequest(): Request
  {
    return $this->request;
  }
}
