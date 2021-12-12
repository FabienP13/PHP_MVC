<?php 

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use App\Session\Session;
use Doctrine\ORM\EntityManager;

class DashboardController extends AbstractController
{
    #[Route(path: "/dashboard")]
    public function getDashboard(EntityManager $em, Session $session) {
     
        
        if(!empty($_SESSION) ){//Si User connecté, on affiche la page
          session_start();
          echo $this->twig->render('dashboard/dashboard.html.twig', [
            'sessionId' => $session->get('id')
        ]);
        } else { //si non connecté + redirection page login + message erreur
          $session->set('notLogged','Vous devez être connecté pour accèder à cette page');
          header("Location: http://localhost:8000/login");
        }
    }
}
