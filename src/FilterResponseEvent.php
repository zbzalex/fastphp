<?php

namespace fastphp;

class FilterResponseEvent extends GetResponseEvent
{
  public function __construct(Request $request, Response $response)
  {
    parent::__construct($request);

    $this->response = $response;
  }
}
