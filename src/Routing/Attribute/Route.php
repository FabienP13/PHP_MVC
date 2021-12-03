<?PHP

namespace App\Routing\Attribute;

use Attribute;

#[Attribute]
class Route
{
    private string $name;
    private string $path;
    private string $httpMethod;

    public function __construct(string $path,string $httpMethod="GET", string $name="default")
    
    {
        $this->path = $path;
        $this->httpMethod = $httpMethod;
        $this->name = $name;
    }



    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of httpMethod
     */ 
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Set the value of httpMethod
     *
     * @return  self
     */ 
    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }
}