<?php 

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Session\Session;

class LogoutController extends AbstractController
{
     /**
     * Permet de se déconnecter
     * Suppression des données de $_SESSION
     *
     * @param EntityManager $em
     * @return void
     */
    #[Route(path: '/logout')]
    public function logout(Session $session)
    {
        session_start();
        var_dump($_SESSION);
        session_destroy();
        
        header("Location: http://localhost:8000/");
        exit();
    }
}
   