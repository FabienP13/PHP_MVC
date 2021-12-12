<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use App\Session\Session;
use DateTime;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractController
{
  #[Route(path: "/")]
  public function index(EntityManager $em, Session $session)
  {
    
    session_start();
    if(!empty($_SESSION) ){ 
    $user = $em->getRepository(User::class)->find($_SESSION['id']);
      
      echo $this->twig->render('index/accueil.html.twig', [
        'sessionSuccess' => $session->get('success'),
        'sessionId' => $session->get('id'),
        'connected' => $session->get('connected'),
        'firstname' => $user->getFirstName()
    ]);
    $session->delete('success');
    $session->delete('connected');
    } else {
      echo $this->twig->render('index/accueil.html.twig');
    }
    
  }
 
}
