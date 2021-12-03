<?php

namespace App\DependecyInjection;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface 
{
    public function __construct(string $service ='service')
    {
        $this->message= $service . 'not found';
    }
}
