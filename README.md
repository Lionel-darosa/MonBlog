# MonBlog
Openclassrooms project 3

## Mettre le projet en ligne

Pour commencer, cloner le projet sur votre poste de travail : `git clone git@github.com:mimo1449/MonBlog.git`

### Installer les dépendances
Il faut ensuite installer toutes les dépendances dans le dossiers vendor, en executant la commande suivante : `composer install`

### Faire un import de la base de données
Importez les tables du fichier blog.sql dans votre base de données

### Modifier les accès à la base de données
Dans le fichier Database.php situé dans le dossier lib, modifier les différents paramètres, permettant 
d'accéder à votre base de données, dans le constructeur comme suit:
```
public function __construct()
    {
        try
        {
            $this->pdo = new \PDO("mysql:dbname=nom de la base de données", "identifiant", "mot de passe", array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'));
        }
        catch (\Exception $e)
        {
            die('Erreur: ' . $e->getMessage());
        }
    }
```

### Lancement du serveur en local
Il suffit d'utiliser le serveur interne de php : `php -S localhost:8080`
