<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/10/17
 * Time: 18:03
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("vendor/autoload.php");

use Lib\Route;
use Lib\Router;

$requestUri = $_SERVER["REQUEST_URI"];

$router = new Router();


$router->add(New Route('Accueil',  '#^/$#', 'Controller\FrontController', 'Accueil'));
$router->add(New Route('Posts',  '#^/articles$#', 'Controller\FrontController', 'Posts'));
$router->add(New Route('Post',  '#^/article/([0-9]+)$#', 'Controller\FrontController', 'Post'));
$router->add(New Route('APropos',  '#^/APropos$#', 'Controller\FrontController', 'About'));
$router->add(New Route('Contact',  '#^/Contact$#', 'Controller\FrontController', 'Contact'));
$router->add(New Route('AddComment',  '#^/article/AddComment/([0-9]+)$#', 'Controller\FrontController', 'AddComment'));
$router->add(New Route('LogIn',  '#^/LogIn$#', 'Controller\FrontController', 'LogIn'));
$router->add(New Route('BackPosts',  '#^/admin/articles$#', 'Controller\BackController', 'Posts'));
$router->add(New Route('BackPost',  '#^/admin/article/([0-9]+)$#', 'Controller\BackController', 'Post'));
$router->add(New Route('NewPost',  '#^/admin/NewArticle$#', 'Controller\BackController', 'NewPost'));
$router->add(New Route('InsertPost',  '#^/admin/InsertArticle$#', 'Controller\BackController', 'InsertPost'));
$router->add(New Route('NewUpdatePost',  '#^/admin/NewUpdateArticle/([0-9]+)$#', 'Controller\BackController', 'NewUpdatePost'));
$router->add(New Route('UpdatePost',  '#^/admin/UpdateArticle/([0-9]+)$#', 'Controller\BackController', 'UpdatePost'));
$router->add(New Route('DeletePost',  '#^/admin/DeleteArticle/([0-9]+)$#', 'Controller\BackController', 'DeletePost'));
$router->add(New Route('DeleteComment',  '#^/admin/DeleteComment/([0-9]+)$#', 'Controller\BackController', 'DeleteComment'));

$route = $router->match($requestUri);

$route->call($requestUri);
