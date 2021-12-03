<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class UserController extends AbstractController
{
  #[Route(path: '/users', name: 'users_list')]
  public function list()
  {
    // Création liste users
    // Ne pas utiliser l'entity manager
    // Créer à l'aide d'une boucle un nombre X d'utilisateurs avec des données fakes
    // Transmettre ensuite ces utilisateurs à la vue
    $users = [];

    echo $this->twig->render('user/list.html.twig', ['users' => $users]);
  }
}
