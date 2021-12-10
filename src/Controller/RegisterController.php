<?php

namespace App\Controller;

use App\Config\Request;
use App\Entity\User;
use App\Routing\Attribute\Route;
use App\Session\Session;
use DateTime;
use Doctrine\ORM\EntityManager;

class RegisterController extends AbstractController
{
    #[Route(path:'/register')]
    public function getRegister(Session $session)
    {
        session_start();
        if(empty($_SESSION)){
            echo $this->twig->render('register/register.html.twig');
        } else {
            $session->set('connected', 'Vous n\'avez pas accès à cette page car vous êtes déjà enregistré. ');
            header("Location: http://localhost:8000/");
        }
        
    }


    #[Route(path:'/register/post', httpMethod:'POST')]
    public function postRegister(EntityManager $em)
    {
                    
            if(!empty($_POST)){
                if(!empty($_POST['name']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['username'])){
                    
                    $emailExist = $em->getRepository(User::class)->findBy(array('email'=>$_POST['email']));
                    if (!$emailExist){
                   
                    $user = new User();
    
                    $user->setName(trim($_POST['name']))
                    ->setFirstName(trim($_POST['firstName']))
                    ->setEmail(trim($_POST['email']))
                    ->setUsername(trim($_POST['username']))
                    ->setPassword(password_hash(trim($_POST['password']), PASSWORD_BCRYPT))
                    ->setBirthDate(new dateTime($_POST['birthday'])); 
                    //commentaire
                    $em->persist($user);
                    $em->flush();
    
    
                    }else{
                        echo 'Email déjà prise';
                    }
                } else {
                    $msg = "Veuillez remplir tout les champs";
                    echo $msg;
                    
                }
            }
 
        
       
    }
}
