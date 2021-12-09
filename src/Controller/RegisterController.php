<?php

namespace App\Controller;

use App\Config\Request;
use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;
use App\Utils\Errors;


class RegisterController extends AbstractController
{
    #[Route(path:'/register')]
    public function getRegister()
    {
        echo $this->twig->render('register/register.html.twig');
    }


    #[Route(path:'/register', httpMethod:'POST')]
    public function postRegister(EntityManager $em, Errors $err)
    {
      
        if(!empty($_POST)){
            //vérifie que les champs sont saisies
            if(!empty($_POST['name']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['username'])){
                
                //verify que l'email rentrée est conforme
                $isValidEmail = $err->validateEmail($_POST['email']);
                var_dump($isValidEmail);
                if($isValidEmail!='false')
                {
                //vérifié si le mail saisie existe en bdd
                $emailExist = $em->getRepository(User::class)->findBy(array('email'=>$_POST['email']));
                // si il n'exsite pas alors mettons en bdd
                    if (!$emailExist)
                    {
                
                    $user = new User();

                    $user->setName(trim($_POST['name']))
                    ->setFirstName(trim($_POST['firstName']))
                    ->setEmail(trim($_POST['email']))
                    ->setUsername(trim($_POST['username']))
                    ->setPassword(password_hash(trim($_POST['password']), PASSWORD_BCRYPT))
                    ->setBirthDate(new dateTime($_POST['birthday'])); 
                    
                    $em->persist($user);
                    $em->flush();

                    

                    }
                //Si l'email existe si elle existe alors on renvoie une erreur
                else{
                   
                    echo $err->errors['usedEmail'];
                    }
                } else {
                    echo $err->errors['valideEmail'];
                }
               
        }
        else {
           
            echo $err->errors['champs'];
            
        }
       
    }
}
}