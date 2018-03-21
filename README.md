# MonBlog
Openclassrooms project 3

## Mettre le projet en ligne

Pour commencer, cloner le projet sur votre poste de travail

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

### Importer le site sur le serveur
copier tout les fichiers du projet, à l'exception de la base de données blog.sql ainsi que le fichier README.md, et les coller 
à la racine du serveur à l'aide d'un logiciel de transfert de fichier qui utilise le protocole ftp (type Filezilla).
