<?php 

namespace App\Controller;

use App\Config\Request;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;
use App\Entity\User;

class LoginController extends AbstractController
{
    #[Route(path: "/login")]
    public function getLogin()
    {
        echo $this->twig->render("login/login.html.twig");
    }

    #[Route(path: "/login", httpMethod: "POST", name: "login")]
    public function postLogin(EntityManager $em)
    {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $users = $em->getRepository(User::class)->findBy(array('email' => $_POST['email']));
            //Si aucun résultat => Mauvais email =>  Message erreur 
            if ($users == null) {
                echo 'Cet email ne correspond à aucun compte';
            } else {
                //si password_verify() retourne true => création session + redirection page + message succès
                if (password_verify($_POST['password'], $users[0]->getPassword())) {
                    session_start();
                    $_SESSION["id"] = $users[0]->getId();
                    $_SESSION["name"] = $users[0]->getName();
                    $_SESSION["firstname"] = $users[0]->getFirstName();
                    $_SESSION["username"] = $users[0]->getUserName();
                    $_SESSION["birthdate"] = $users[0]->getBirthDate();
                    echo '<h1>true</h1>';
                    
                }
                //si password_verify() retourne false => mauvais mdp => message erreur
                else {
                    echo 'false';
                }
            }
        }
     
    }
}