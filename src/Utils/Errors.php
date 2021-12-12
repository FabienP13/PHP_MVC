<?php

namespace App\Utils;

class Errors 
{

    //tableau des erreurs
  
public $errors = [
    'usedEmail'=> "L'email est déjà prise, veuillez en choisir une autre",
    'valideEmail'=>"L'email n'est pas valide, veuillez en saisir une conforme",
    'password' => "Le mot de passe est trop court, veuillez entrer au moins 6 caractères",
    'champs' => "Tout les champs doivent être renseignés !"
];

public function __construct()
{
    
}

    function validateEmail(string $email) {

        return filter_var($email, FILTER_VALIDATE_EMAIL);

    }
}