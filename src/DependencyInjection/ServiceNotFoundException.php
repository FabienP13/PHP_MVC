<?PHP

namespace App\DependencyInjection;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(string $service ='Service')
    {
        $this->message = $service .' not found';
    }
}