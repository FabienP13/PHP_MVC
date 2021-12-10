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
        session_start();
        var_dump($_SESSION);
        if(!empty($_SESSION) ){
        $user = $em->getRepository(User::class)->find($_SESSION['id']);
        
          echo $this->twig->render('dashboard/dashboard.html.twig', [
            'sessionId' => $session->get('id'),
            'firstname' => $user->getFirstName()
        ]);
        } else {
          echo $this->twig->render('dashboard/dashboard.html.twig');
        }
    }
}
