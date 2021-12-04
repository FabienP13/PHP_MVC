<?php 

namespace App\Controller;

use App\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route(path: "/login")]
    public function getLogin()
    {
        echo $this->twig->render("login/login.html.twig");
    }

    #[Route(path: "/login", httpMethod: "POST", name: "login")]
    public function postLogin()
    {
     
     if(isset($_POST)&& isset($_POST['email']) && isset($_POST['password'])){
        
     }
     
    }
}