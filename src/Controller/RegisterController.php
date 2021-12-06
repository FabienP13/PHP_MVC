<?php

namespace App\Controller;

use App\Config\Request;
use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;

class RegisterController extends AbstractController
{
    #[Route(path:'/register')]
    public function getRegister()
    {
        echo $this->twig->render('register/register.html.twig');
    }


    #[Route(path:'/register', httpMethod:'POST')]
    public function postRegister(EntityManager $em)
    {
        if(!empty($_POST)){
            if(!empty($_POST['name']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['username'])){

               
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

            } else {
                $msg = "il y a erreur";
                echo $msg;
            }
        }
       
    }
}