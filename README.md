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
   --> supprimer caractères spéciaux