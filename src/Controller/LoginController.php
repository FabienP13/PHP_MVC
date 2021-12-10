<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;
use App\Entity\User;
use App\Session\Session;

class LoginController extends AbstractController
{

    /**
     * Affiche le formulaire de connexion
     *
     * @return void
     */
    #[Route(path: "/login")]
    public function getLogin()
    {
        var_dump($_SESSION);
        echo $this->twig->render("login/login.html.twig");
    }

    /**
     * Permet de se connecter en vérifiant les identifiants
     * Récupère infos de l'user connecté + redirection
     * Gestion erreur mauvais identifiants 
     *
     * @param EntityManager $em
     * @param Session $session
     * @return void
     */
    #[Route(path: "/login", httpMethod: "POST", name: "login")]
    public function postLogin(EntityManager $em, Session $session)
    {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $user = $em->getRepository(User::class)->findOneBy(array('email' => $_POST['email']));

            //Si aucun résultat => Mauvais email =>  Message erreur 
            if ($user == null) {
                $session->set('errorEmail', 'Cet email est lié à aucun compte');
                echo $this->twig->render('login/login.html.twig', [
                    'badEmail' => $session->get('errorEmail')
                ]);
                $session->delete('errorEmail');
            } else {
                //si password_verify() retourne true => création session + redirection page + message succès
                if (password_verify($_POST['password'], $user->getPassword())) {

                    session_start();
                    $session->set('id',$user->getId());
                    $session->set('success', 'Vous êtes connecté ');
                    
                    header("Location: http://localhost:8000/");
                    exit();
                   
                }
                //si password_verify() retourne false => mauvais mdp => message erreur
                else {
                    $session->set('errorPw', 'Mauvais password');
                    echo $this->twig->render('login/login.html.twig', [
                        'badPassword' => $session->get('errorPw')
                    ]);
                    $session->delete('errorPw');
                }
            }
        }
    }

}
