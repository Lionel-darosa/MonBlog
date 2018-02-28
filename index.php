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


$router->add(New Route('Accueil',  '#^/$#', 'Controller\FrontController', 'Accueil', '0'));
$router->add(New Route('Posts',  '#^/articles\?page=([0-9]+)$#', 'Controller\FrontController', 'Posts','0'));
$router->add(New Route('Post',  '#^/article/([0-9]+)\?page=([0-9]+)$#', 'Controller\FrontController', 'Post', '0'));
$router->add(New Route('APropos',  '#^/APropos$#', 'Controller\FrontController', 'About', '0'));
$router->add(New Route('Contact',  '#^/Contact$#', 'Controller\FrontController', 'Contact', '0'));
$router->add(New Route('SignalComment',  '#^/article/SignalComment/([0-9]+)\?page=([0-9]+)$#', 'Controller\FrontController', 'Signal', '0'));
$router->add(New Route('LogIn',  '#^/LogIn$#', 'Controller\FrontController', 'LogIn', '0'));
$router->add(New Route('LogInControl',  '#^/LogIn/Control$#', 'Controller\FrontController', 'LogInControl', '0'));
$router->add(New Route('BackPosts',  '#^/admin/articles\?page=([0-9]+)$#', 'Controller\BackController', 'Posts', '1'));
$router->add(New Route('BackComments',  '#^/admin/comments\?page=([0-9]+)$#', 'Controller\BackController', 'Comments', '1'));
$router->add(New Route('BackPost',  '#^/admin/article/([0-9]+)$#', 'Controller\BackController', 'Post', '1'));
$router->add(New Route('NewPost',  '#^/admin/NewArticle$#', 'Controller\BackController', 'NewPost', '1'));
$router->add(New Route('NewUpdatePost',  '#^/admin/NewUpdateArticle/([0-9]+)$#', 'Controller\BackController', 'NewUpdatePost', '1'));
$router->add(New Route('DeletePost',  '#^/admin/DeleteArticle/([0-9]+)$#', 'Controller\BackController', 'DeletePost', '1'));
$router->add(New Route('DeleteComment',  '#^/admin/DeleteComment/([0-9]+)$#', 'Controller\BackController', 'DeleteComment', '1'));
$router->add(New Route('DeleteCommentPage',  '#^/admin/DeleteCommentPage/([0-9]+)$#', 'Controller\BackController', 'DeleteCommentPage', '1'));
$router->add(New Route('LogOut',  '#^/admin/LogOut$#', 'Controller\BackController', 'LogOut', '1'));

$route = $router->match($requestUri);
$route->call($requestUri);
