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


    #[Route(path:'/register/post', httpMethod:'POST')]
    public function postRegister(EntityManager $em, Errors $err)
    {
      
        if(!empty($_POST)){
            //vérifie que les champs sont saisies
            if(!empty($_POST['name']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['username'])){
                
               
                //vérifie que l'email est valide 
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                //On a pas mis $isValidEmail=='true' car la fonction validateEmail return soit false soit l'email et donc pas true
                {
                //vérifié si le mail saisie existe en bdd
                $emailExist = $em->getRepository(User::class)->findBy(array('email'=>$_POST['email']));
                // si il n'exsite pas alors mettons en bdd
                    if (!$emailExist)
                    {
                        if(strlen($_POST['password'])> 5 )
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

                        //var_dump($isValidEmail);
                        header("Location: http://localhost:8000/login");
                        exit();

                       
                        } else {
                            $msg = $err->errors['password'];
                            echo $this->twig->render('register/register.html.twig', [
                                'msg' =>$msg
                                
                            ]);
                        }



                    }
                //Si l'email existe si elle existe alors on renvoie une erreur
                else{
                   
                    $msg = $err->errors['usedEmail'];
                    echo $this->twig->render('register/register.html.twig', [
                        'msg' =>$msg
                        
                    ]);
                    }
                } else {
                    $msg = $err->errors['valideEmail'];
                    echo $this->twig->render('register/register.html.twig', [
                        'msg' =>$msg
                        
                    ]);
                }
               
        }
        else {
           
            $msg = $err->errors['champs'];
                    echo $this->twig->render('register/register.html.twig', [
                        'msg' =>$msg
                        
                    ]);
            
        }
       
    }
}
}   