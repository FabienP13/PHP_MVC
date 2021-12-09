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
    
    $user->getIsAuth();
    
      echo $this->twig->render('index/accueil.html.twig', [
        'sessionSuccess' => $session->get('success'),
        'isAuth' => $user->getIsAuth(),
        'firstname' => $user->getFirstName()
    ]);
    $session->delete('success');
    } else {
      $session->set('logout', 'Vous êtes déconnecté!');
      echo $this->twig->render('index/accueil.html.twig',[
        'logout' => $session->get('logout')
      ]);
      $session->delete('logout');
    }
    
  }
 
}
