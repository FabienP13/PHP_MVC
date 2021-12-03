<?PHP

namespace App\Tests\DependencyInjection;

use App\DependencyInjection\Container;
use App\DependencyInjection\ServiceNotFoundException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerTest extends TestCase
{
    private Container $container;

    public function setUp():void
    {
        $this->container = new Container();
    }
    public function testAddThenGetService()
    {
       
        $service = new stdClass();
       $this->container->set('test', $service);

        $retrieveService =$this->container->get('test');
        $this->assertSame($service, $retrieveService);
    }

    public function testHasService()
    {
       
        $service = new stdClass();
       $this->container->set('test', $service);

        $hasService =$this->container->has('test');
        $this->assertTrue($hasService);
    }

    

    public function testHasNotService()
    {
       
        $hasService =$this->container->has('test');
        $this->assertFalse($hasService);
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

    public function testSetDuplicate()
    {
       
        $service = new stdClass;
       $this->container->set('test', $service);
        $this->expectException(InvalidArgumentException::class);
       $this->container->set('test',$service);
    }

    public function testSetDuplicateServiceThroxsException()
    {
        $service = new stdClass();
        $this->container->set('test',$service);
        $this->expectException(InvalidArgumentException::class);
        $this->container->set('test',$service);
    }
}