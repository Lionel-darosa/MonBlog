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


$router->add(New Route('Accueil',  '#^/$#', 'Controller\FrontController', 'accueil', '0'));
$router->add(New Route('Posts',  '#^/articles\?page=([0-9]+)$#', 'Controller\FrontController', 'posts','0'));
$router->add(New Route('Post',  '#^/article/([0-9]+)\?page=([0-9]+)$#', 'Controller\FrontController', 'post', '0'));
$router->add(New Route('APropos',  '#^/APropos$#', 'Controller\FrontController', 'about', '0'));
$router->add(New Route('Contact',  '#^/Contact$#', 'Controller\FrontController', 'contact', '0'));
$router->add(New Route('SignalComment',  '#^/article/SignalComment/([0-9]+)\?page=([0-9]+)$#', 'Controller\FrontController', 'signal', '0'));
$router->add(New Route('LogIn',  '#^/LogIn$#', 'Controller\FrontController', 'logIn', '0'));
$router->add(New Route('LogInControl',  '#^/LogIn/Control$#', 'Controller\FrontController', 'logInControl', '0'));
$router->add(New Route('BackPosts',  '#^/admin/articles\?page=([0-9]+)$#', 'Controller\BackController', 'posts', '1'));
$router->add(New Route('BackComments',  '#^/admin/comments\?page=([0-9]+)$#', 'Controller\BackController', 'comments', '1'));
$router->add(New Route('BackPost',  '#^/admin/article/([0-9]+)$#', 'Controller\BackController', 'post', '1'));
$router->add(New Route('NewPost',  '#^/admin/NewArticle$#', 'Controller\BackController', 'newPost', '1'));
$router->add(New Route('NewUpdatePost',  '#^/admin/NewUpdateArticle/([0-9]+)$#', 'Controller\BackController', 'newUpdatePost', '1'));
$router->add(New Route('DeletePost',  '#^/admin/DeleteArticle/([0-9]+)$#', 'Controller\BackController', 'deletePost', '1'));
$router->add(New Route('DeleteComment',  '#^/admin/DeleteComment/([0-9]+)$#', 'Controller\BackController', 'deleteComment', '1'));
$router->add(New Route('DeleteCommentPage',  '#^/admin/DeleteCommentPage/([0-9]+)$#', 'Controller\BackController', 'deleteCommentPage', '1'));
$router->add(New Route('LogOut',  '#^/admin/LogOut$#', 'Controller\BackController', 'logOut', '1'));

$route = $router->match($requestUri);
$route->call($requestUri);
