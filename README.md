- [Introduction](#introduction)
- [Register](#register)
  - [La vue](#la-vue)
  - [Le controller](#le-controller)
- [Récupérer les requêtes POST](#récupérer-les-requêtes-post)
- [Login](#login)
  - [Récupérer les informations de la personne qui se connecte](#récupérer-les-informations-de-la-personne-qui-se-connecte)
- [Session](#créer-une-session)
- [Les redirections](#les-redirections)
- [Authentification](#authentification)
  - [Méthode n°1](#méthode-1)
  - [Méthode n°2](#méthode-2)
- [Gestion des erreurs](#message-erreur)
  - [Partie Register](#partie-register)
  - [Partie Login](#partie-login)

## Introduction 

Pour ce projet, nous avons choisi d'ajouter à la base réalisée avec vous la possibilité à un utilisateur de pouvoir s'enregister, de se connecter, de limiter l'accès à certaines selon si on est connecté ou non, de créer des messages d'erreurs et chercher à valider nos formulaires.

Nous allons donc tenter d'expliquer le plus clairement possible le déroulé de notre projet ainsi que les problèmes auxquels on a du faire face.

## Register

### La vue 
Premièrement, on a commencé par créer la vue (‘register.twig.html), où l’utilisateur peut remplir les champs pour créer un compte et s'enregistrer. 
Nous avons donc mis un formulaire avec des inputs de type « text » pour les champs nom, prénom et username, un input de type « email » pour l’email, un input « date » pour le birthday et un input de type « password » pour le mot de passe. Sans oublier le bouton dans le formulaire qui prendra le type « submit » pour soumettre les informations. Le formulaire prendra l’action « /register » et la méthode « POST », cela fera référence à la route que l’on aura défini pour la fonction « postRegister » :

`
<form method="POST" action="/register/post">

<p>Nom :</p>
<input type="text" name="name" required>

<p>Prénom :</p>
<input type="text" name="firstName" required>

<p>Date d'anniversaire :</p>
<input type="date" name="birthday" required>

<p>E-mail :</p>
<input type="email" name="email" required>

<p>Username :</p>
<input type="text" name="username" required>

<p>Mot de passe :</p>
<input type="password"name="password" required>

<button type="submit">S'enregistrer</button>
        

</form>
`

Ainsi ça route sera "#[Route(path:'/register')]".
Pour accéder à  la vue du formulaire, nous avons créer une fonction "getRegister" qui retournera "/register" en "get".


### Le controller
Deuxièmement, nous avons créé la fonction "postRegister" (en paramètre EntityManager et Errors),
Cette fonction va gérer toutes les conditions liées à l'inscription, vérifier
que le mot de passe possède au minimum 6 caractères, que l'addresse mail ne soit pas 
déjà utilisée et vérifier qu'elle soit valide.
ainsi ça route sera "#[Route(path:'/register/post')]".

-Nous vérifierons que tous les champs soient remplis avec la condition :

`if(!empty($_POST['name']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['username']))`

-Puis nous vérifions que l'address soit conforme :

`if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))`

-Ensuite nous vérifions que l'adresse mail n'existe pas en base de données :

`$emailExist = $em->getRepository(User::class)->findBy(array('email'=>$_POST['email']));
if (!$emailExist)`

-Nous vérifions que le mot de passe fasse au moins 6 caractères :

`if(strlen($_POST['password'])> 5 )`

Il fallait également hasher le mot de passe pour éviter qu'il soit affiché en brut dans la base de données. Pour cela nous utilisons la fonction suivante : 

`password_hash(trim($_POST['password']), PASSWORD_BCRYPT)` 

-Pour finir une fois que toutes les conditions sont réspectées nous pouvons envoyer le formulaire en base de données et rediriger vers le login :

```php

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
                        exit();`
```
## Récupérer les requêtes POST

On a essayé de créer une classe Request.php et de l'injecter dans notre fonction postRegister() et postLogin() pour récupréer l'ensemble des champs input, mais lorsque l'on appelait la fonction en question, on avait une erreur qui signalait qu'il manquait un argument à la fonction `postLogin()`/ `postRegister()`.

On a tenté d'ajouter la request dans la function `'call_user_func_array()'` de la fonction `execute()` de notre Router.php, mais impossible car la fonction ne prenait que 2 paramètres : le calllback et un tableau d'arguments (ici $params) et on n'a jamais réussi à ajouter la request à ce tableau $params.
On a donc décider d'utiliser directement les `$_POST`.
  

## Login  

Tout d'abord, on a créé une simple fonction `getLogin()` qui renvoie le formulaire de connexion: 
```php
  #[Route(path: "/login")]
    public function getLogin()
    {
        echo $this->twig->render("login/login.html.twig");
    }
```
Nous avons également la fonction `postLogin()` où on vérifie que les deux champs sont bien remplis. Elle permet de vérifier si le compte existe, et si les identifiants sont correctement renseignés : 

```php 
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
```
### Récupérer les informations de la personne qui se connecte
On a essayé de faire directement dans la fonction `postLogin()` une requête sql pour les récupérer directement.
Puis on a utilisé l'entityManager avec la fonction :
> $user = $em->find(User:class, $id)

Sauf qu'on n'a pas trouvé directement de moyen le récupérer l'ID de la personne qui voulait se connecter. 

On a finalement trouvé en utilisant l'entityManager pour récupérer un repository `(App\Entity\User)` grâce à l'email renseignée par la personne qui se connectait : 
> $users = $em->getRepository(User::class)->findBy(array('email' => $_POST['email']));

## Créer une session

On crée une classe `Session.php` qui permet d'associer une clé et une valeur dans le tableau `$_SESSION`. Cette classe est composée de 4 méthodes dont 1 privée et 3 publiques : 
    - ensureStarted() qui vérifie si une session est créée.
    - get($key) qui récupère une valeur en passant une clé en paramètre.
    - set($key, $value) qui ajoute une valeur à une clé dans la superglobale $_SESSION.
    - delete($key) qui supprime une clé et sa valeur.

Dans un premier temps, on a rajouté un **`attribut boolean isAuth`** dans notre entity `User.php`  qui a une valeur par défault 'false'.

Quand un user se connecte, dans la fonction `postLogin()` de notre `LoginController`, après avoir vérifié que les identifiants étaient identiques à ceux enregistrés dans la base de données, on passe cette variable à 'true' pour que l'on puisse savoir si un utilisateur est connecté ou non.
    
Après vos conseils, on a supprimé cette manière de fonctionner pour éviter de faire des interactions inutiles avec la BDD et éviter qu'il y ait des failles dans notre projet comme par exemple si l'utilisateur ferme le navigateur sans se déconnecter. Nous avons donc choisi d'utilser la ***`Superglobale $_SESSION`*** et notre classe Session.php pour **créer** une variable `$_SESSION['id']` dont on affectera l'id de la personne connectée : 
> $session->set('id',$user->getId());

On a donc utilisé notre classe Session.php et ces méthodes pour conserver des informations sur la personne qui se connecte et également ajouter des messages d'erreurs. 

Pour cela, on instancie notre classe Session dans notre **`fichier bootstrap index.php`**, on l'insére dans le container et on l'injecte directement dans la fonction qui doit avoir accès aux informations de la session en ajoutant en première ligne de notre controller un `session_start()` pour créer une nouvelle session ou reprendre une existante.

## Les redirections 
Pour effectuer une redirection, nous utilisons la fonction:
> header('Location : *URL*)

Dans un premier temps, nous l'utilisions juste avant d'afficher notre view comme ceci : 
```php
header("Location: http://localhost:8000/");
echo $this->twig->render('login/login.html.twig', [
    'sessionSuccess' => $session->get('success'),
    'isAuth' => $user->getIsAuth(),
    'firstname' => $user->getFirstName()
]);
```

Mais après votre remarque, on a remplacé le ***echo*** par un ***`exit()`*** car même après la redirection faite, le code situé après va quand même être exécuté. 

Concernant les informations que l'on passait à notre view, comme elles sont enregistrées en session, on les passait en paramètre de notre "view de destination" afin d'afficher un message d'alerte après s'être connecté par exemple :

```php 
$user = $em->getRepository(User::class)->find($_SESSION['id']);
      
      echo $this->twig->render('index/accueil.html.twig', [
        'sessionSuccess' => $session->get('success'),
        'sessionId' => $session->get('id'),
        'connected' => $session->get('connected'),
        'firstname' => $user->getFirstName()
    ]);
```

## Authentification

Dans le but de restreindre l'accès à la page dashboard (seulement si connecté) ou à la page Register(si on est déjà connecté), on a essayé 2 voies :

### Méthode 1

Tout d'abord, nous avons cherché à ajouter une **nouvelle propriété** aux attributs d'une route. Par exemple, pour la route dashboard faire comme ceci : 
> #[Route(path: "/dashboard", protected:"true")]

Pour le réaliser, dans la classe `Routing/Attribute/Route`.php, on a rajouté un attribut `private string $protected` que l'on a declaré, assigné une valeur dans le contructeur et ajouté les Getter et Setter correspondant.

Ensuite nous avons ajouté cet attribut dans notre routeur `Router.php` dans la fonction `addRoute()` , dans la fonction `getRoute()` en l'ajoutant comme condition dans la boucle foreach qui parcoure le tableau de Routes :

```php
if ($route['url'] === $uri && $route['http_method'] === $httpMethod && $route['protected'] === $protected) {
    return $route;
}
```

On a également ajouté l'attribut comme paramètre de la fonction `execute()` et la fonction `registerRoutes()`: 

```php
foreach($attributes as $attribute)
{
    /** @var Route */
    $route = $attribute->newInstance();
    $this->addRoute(
    $route->getName(),
    $route->getPath(),
    $route->getHttpMethod(),
    $fqcn,
    $method->getName(),
    $protected->getProtected()
    );
}
```

Dans notre **fichier bootstrap** de notre projet, il a fallu ajouter cet attribut en paramètre de la fonction `execute()`. 
Notre problème est arrivé à ce moment là : 
nous avons jamais réussi à modifier la valeur de l'attribut **`$protected`** quand une session était créée. 

### Méthode 2

On a donc opté pour une seconde option pour restreindre l'accès à certaines pages :
> if(!empty($_SESSION) " dans nos controlleurs.

Nous sommes conscient que ce n'est pas la meilleure option mais nous voulions que notre projet soit fonctionnel.

## Message erreur 

Nous avons utilisé une façon différente de gérer les erreurs dans login et register pour présenter
les différentes manières mais également car nous étions à l'aise qu'avec notre méthode.
Bien entendu, nous sommes conscients qu'il faille utiliser qu'une seul manière de gérer les erreurs pour question 
de bonne pratiques !

### Partie register

Il a fallu gérer les erreurs (dans le cas où les conditions vu dans le [controller](#le-controller) ne sont pas respéctées), pour cela nous avons créer une classe `Errors.php`, en y mettant les attributs et un tableau d'Errors. Ce tableau d'erreurs se présente sous cette forme :

```php
public $errors = [
    'usedEmail'=> "L'email est déjà prise, veuillez en choisir une autre",
    'valideEmail'=>"L'email n'est pas valide, veuillez en saisir une conforme",
    'password' => "Le mot de passe est trop court, veuillez entrer au moins 6 caractères",
    'champs' => "Tout les champs doivent être renseignés !"
];
```

On appelera une erreur de la sorte (ici l'erreur "Le mot de passe est trop court, veuillez entrer au moins 6 caractères"): 

`$msg = $err->errors['password'];`

**`$err`** étant un Objet de la classe ***Errors.php***, nous récupérons l'erreur qui a la clé 'password' dans la tableau `$errors`.


Pour la validation de l’email, on aurait pu utiliser directement en html le type « email » dans l’input mais il existe un problème : on peut contourner la saisie de l’email en inspectant l’élément sur l’input puis retirer le type « email », de là nous pouvons valider le formulaire sans que l’email soit conforme.
On a donc utilisé directement la validation de l’email dans le php grâce à une méthode native, dans notre cas :

`filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)`

Cela va récupérer la valeur de l'input "email" et va vérifier si la valeur saisie est conforme ou non en renvoyant false si 
le mail n'est pas conforme ou en renvoyant l'email si elle est conforme.

### Partie Login 

Pour ajouter des messages liés à la connexion, au restriction d'accès des pages, nous allons utiliser les méthodes `set()` and `get()` de notre classe `Session.php`. Voici un **exemple** de la création d'**un message d'erreur** :
```php
if ($user == null) {
    $session->set('errorEmail', 'Cet email est lié à aucun compte');
    echo $this->twig->render('login/login.html.twig', [
            'badEmail' => $session->get('errorEmail')
            ]);
    $session->delete('errorEmail');
    }
```

On ajoute donc une valeur à la clé `errorMail` et on assigne cette valeur à la variable `badEmail` passée en paramètre de notre view pour pouvoir l'afficher.
Pour l'afficher, on va mettre une condition pour savoir si la variable est vide, dans le cas contraire, on l'affiche :
> {% if badEmail %}

> {{badEmail}}

> {% endif %}

On s'est rendu compte que ce message persistait vu qu'il était enregistré dans `$_SESSION`. On a donc rajouter une ligne après l'affichage de notre view pour supprimer ce message : 

> $session->delete('errorEmail');
        
C'est fonctionnel, mais nous sommes conscient que ce n'est pas optimisé !




## Modification visuelle
Pour la modification visuelle de notre navbar si l'utilisateur est connecté ou non, nous avons utilisé le même principe : 
Nous avons créé une variable 'sessionId' après avoir vérifié que les identifiants renseignés correspondaient avec ceux enregistrés dans la base de données. 
On ajoute donc une condition dans notre header de nos views qui vérifie si la session est créée et affiche le menu adéquat : 
```php

    {% if sessionId == null %}
						
		<li class="nav-item ">
			<a class="nav-link" href="/register">Register</a>
		</li>
	    <li class="nav-item ">
			<a class="nav-link" href="/login">Login</a>
		</li>
					
	{% else %}
					
		<li class="nav-item ">
			<a class="nav-link" href="/dashboard">Dashboard</a>
		</li>
					
		<li class="nav-item ">
		    <a class="nav-link" href="/logout">Logout</a>
		</li>
	{% endif %}
```  