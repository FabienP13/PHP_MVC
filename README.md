1- Faire une page register qui permet d'ajouter un utilisateur à la base
    --> Création d'une view (formulaire d'enregistrement)
        --> Assigner une route / method / (nom) pour afficher le formulaire 
        --> Création d'une route et méthode traitement de la requête 
    --> Création registerController
        --> Création d'une fonction pour afficher le formulaire
        --> Création d'une fonction pour récupérer les données de la requête et les insérer dans la BDD
        --> Encodage du password
    

2- Faire une page login pour se connecter
    --> Création d'une view Login (formulaire)
        --> Assigner une route / method / (nom) pour afficher formulaire
        --> Créer une route et une méthode pour traiter les informations 
    --> Création loginController
        --> Création fonction pour afficher la page 
        --> Création fonction pour comparer les informations renseignées dans le formulaire avec ceux enregistrées dans la BDD
            --> Décodage du password
            --> Gestion des erreurs 
            --> Validation des formulaires 
            
3- Gérer les droits d'accès 
    --> Autoriser l'accès à des pages sous condition(s)
    --> Modifier affichage selon statut (enregistré ou non)


PROBLEMES :

1- Recupérer la requete post
On a essayé de créer une classe Request.php et de l'injecter dans notre fonction postRegister() et postLogin() pour récupréer l'ensemble des champs input, mais lorsque l'on appelait la fonction en question, on avait une erreur qui signalait qu'il manquait un argument à la fonction postLogin()/postRegister().
On a tenté d'ajouter la request dans la function 'call_user_func_array' dans la fonction execute() de notre Router.php, mais impossible car la fonction ne prenait que 2 paramètres : le calllback et un tableau d'arguments (ici $params) et on n'a jamais réussi à ajouter la request à ce tableau $params.
On a donc décider d'utiliser directement les $_POST.
  

2- Récupérer les informations de la personne qui se connecte
On a essayé de faire directement dans la fonction postLogin() une requête sql pour les récupérer directement.
Puis on a utilisé l'entityManager avec la fonction : $user = $em->find(User:class, $id). Sauf qu'on n'a pas trouvé de moyen de récupérer l'ID de la personne qui voulait se connecter. 

On a finalement trouvé en utilisant l'entityManager pour récupérer un repository (App\Entity\User) grâce à l'email renseignée par la personne voulant se connecter avec la fonction : 
$users = $em->getRepository(User::class)->findBy(array('email' => $_POST['email'])); 
