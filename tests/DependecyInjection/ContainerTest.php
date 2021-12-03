<?php 

namespace App\Tests\DependecyInjection;

use App\DependecyInjection\Container;
use App\DependecyInjection\ServiceNotFoundException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerTest extends TestCase
{
  private Container $container;

  public function setUp(): void
  {
    $this->container = new Container();
  }

  public function testAddThenGetService()
  {
    $service = new stdClass();
    $this->container->set('test', $service);

    $retrievedService = $this->container->get('test');
    $this->assertSame($service, $retrievedService);
  }

  public function testHasNotService()
  {
    $hasService = $this->container->has('test');
    $this->assertFalse($hasService);
  }

  public function testHasService()
  {
    $service = new stdClass();
    $this->container->set('test', $service);

    $hasService = $this->container->has('test');
    $this->assertTrue($hasService);
  }

  public function testServiceNotFound()
  {
    $this->expectException(ServiceNotFoundException::class);
    $this->container->get('test');
  }

  public function testSetService()
  {
    $service = new stdClass();
    $this->container->set('test', $service);
    $this->assertTrue($this->container->has('test'));
  }

  public function testSetDuplicateServiceThrowsException()
  {
    $service = new stdClass();
    $this->container->set('test', $service);
    $this->expectException(InvalidArgumentException::class);
    $this->container->set('test', $service);
  }
}
