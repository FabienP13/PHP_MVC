<?php 

namespace App\Controller;

use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;
use App\Entity\User;
use App\Session\Session;

class LoginController extends AbstractController
{
    #[Route(path: "/login")]
    public function getLogin()
    {
        echo $this->twig->render("login/login.html.twig");
    }

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

            } else {
                //si password_verify() retourne true => création session + redirection page + message succès
                if (password_verify($_POST['password'], $user->getPassword())) {

                    session_start();
                    $_SESSION["id"] = $user->getId();
                    $_SESSION["name"] = $user->getName();
                    $_SESSION["firstname"] = $user->getFirstName();
                    $_SESSION["username"] = $user->getUserName();
                    $_SESSION["birthdate"] = $user->getBirthDate();
                    $user->setIsAuth(true);
                    
                    $session->set('success', 'Vous êtes connecté ');
                    
                    echo $this->twig->render('dashboard/dashboard.html.twig', [
                        'sessionSuccess' => $session->get('success'),
                        'isAuth' => $user->getIsAuth(),
                        'firstname' => $_SESSION['firstname']
                    ]);
                    
                }
                //si password_verify() retourne false => mauvais mdp => message erreur
                else {
                    $session->set('errorPw', 'Mauvais password');
                    echo $this->twig->render('login/login.html.twig', [
                    'badPassword' => $session->get('errorPw')
                ]);
                }
                
            }
        }
     
    }
}