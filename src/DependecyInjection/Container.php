<?php

namespace App\DependecyInjection;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{

    private array $services = [];

    public function get(string $id){
        if(!$this->has($id)){
            throw new ServiceNotFoundException($id);
        }
        return $this->services[$id];
    }
    public function has(string $id){
        return array_key_exists($id, $this->services);
    }

    public function set(string $id, object $service){

        if ($this->has($id)){
            throw new InvalidArgumentException(sprintf('The "%s" service is alrready initialized, you cannot replace it.', $id));
        }
        $this->services[$id] = $service;
    }
}