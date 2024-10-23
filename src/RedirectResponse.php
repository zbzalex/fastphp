<?php

namespace fastphp;

class RedirectResponse extends Response
{
  public function __construct($redirectUrl)
  {
    parent::__construct(null, [
      'location' => $redirectUrl
    ]);
  }
}