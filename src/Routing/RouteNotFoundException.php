<?php

namespace App\Routing;

use Exception;

class RouteNotFoundException extends Exception
{
  public function __construct()
  {
    $this->message = "Route not found";
  }
}
