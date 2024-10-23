<?php

namespace fastphp;

class Route
{
  private $requestMethod;
  private $path;
  private $controller;
  private $compiledPath;
  private $args;

  public function __construct(
    $requestMethod,
    $path,
    $controller = null
  ) {
    $this->requestMethod = $requestMethod;
    $this->path = $path;
    $this->controller = $controller;
    $this->args = [];
  }

  public function compile()
  {
    $that = $this;

    $lastChar = substr($this->path, -1);
    $path = str_replace(["/", ")"], ["\/", ")?"], $this->path);

    // match params
    $path = preg_replace_callback("/@([\w]+)(:([^\/\(\)]*))?/", function ($matches) use ($that) {
      $that->args[] = $matches[1];

      if (isset($matches[3])) {
        return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
      }

      return "(?P<" . $matches[1] . ">[^/\?]+)";
    }, $path);

    if ($lastChar == '/') {
      $path .= "?";
    } else {
      $path .= "\/?";
    }

    $this->compiledPath = "/"
      . "^"    // start
      . $path
      . "(?:\?.*)?"
      . "$"   // end
      . "/i"  // flags
    ;
  }

  public function getRequestMethod()
  {
    return $this->requestMethod;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getController()
  {
    return $this->controller;
  }

  public function getCompiledPath()
  {
    return $this->compiledPath;
  }

  public function getArgs()
  {
    return $this->args;
  }
}
