<?php

namespace App\Controller;

class UserController extends AbstractController
{
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
