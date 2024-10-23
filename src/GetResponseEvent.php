<?php

namespace fastphp;

class GetResponseEvent extends BaseEvent
{
  protected $response;

  public function setResponse(Response $response)
  {
    $this->response = $response;
  }
  
  public function getResponse()
  {
    return $this->response;
  }

  public function hasResponse()
  {
    return $this->response !== null;
  }
}
