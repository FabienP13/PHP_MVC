## Register

1. Pour commencer on a commencé par créer la vue (‘register.twig.html), grâce à cette vue
l’utilisateur peut remplir les champs pour se connecter. Dans cette vue nous y mettrons un 
formulaire avec des input de type « text » pour les noms, prénoms et username, un input de type « email » pour l’email, un input « date » pour le birthday et un input de type « password » pour le mot de passe. Sans oublier le bouton dans le formulaire qui prendra le type « submit » pour soumettre les informations. Le formulaire prendra l’action « /register » et la méthode « POST », cela fera référence à la route que l’on aura défini pour la fonction « postRegister ». Celui-ci prendra en méthode "POST" et en action "/register",
ainsi ça route sera "#[Route(path:'/register')]".
Pour accéder à  la vue du formulaire, nous avons créer une fonction "getRegister" qui retournera "/register" en "get".


2. Ensuite, nous avons créer la fonction "postRegister",
Cette fonction va gérer toutes les conditions liés à l'inscription, vérifier
que le mot de passe possède au minimum 6 caractères, que l'addresse mail ne soit pas 
déjà utilisé, vérifier également que l'addresse mail soit valide.


3. Pour finir, il a fallu gérer les erreurs, pour cela nous avons créer une classe "Errors", en y mettant 
les attibuts, un tableau d'Errors. Ce tableau d'erreurs se présentera sous la forme :


```public $errors = [
    'usedEmail'=> "L'email est déjà prise, veuillez en choisir une autre",
    'valideEmail'=>"L'email n'est pas valide, veuillez en saisir une conforme",
    'password' => "Le mot de passe est trop court, veuillez entrer au moins 6 caractères",
    'champs' => "Tout les champs doivent être renseignés !"
];```

