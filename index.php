<?php 
session_start();
require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index",$data = array(1,2));
});
$app->get('/admin', function() {
    
	User::verifyLogin();
	
	$page = new PageAdmin();
	$page->setTpl("index",$data = array(1,2));
});
$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});
$app->post('/admin/login', function() {
    
	User::login($_POST["login"],$_POST["password"]);
	header ("location: /admin");
	exit;
});
$app->get('/admin/logout', function() {
    
	User::logout();
	header("location: /admin/login");
	exit;
});
//GET -> ALL USER
$app->get('/admin/users', function() {
    User::verifyLogin();
	
	$users = User::ListaAll();
	
	$page = new PageAdmin();
	
	$page->setTpl("users", array(
		"users" => $users
	));	
});
//GET -> CREATE USER
$app->get('/admin/users/create', function() {
   
	User::verifyLogin();
	
	$page = new PageAdmin();
	
	$page->setTpl("users-create");
});
//GET -> DELETE
$app->get('/admin/users/:iduser/delete', function($iduser) {
	
	User::verifyLogin();
	
	$user = new User();
	
	$user->get((int)$iduser);
	
	$user->delete();
	
	header("Location: /admin/users");
	exit;
	
});
//GET -> EDIT USER
$app->get('/admin/users/:iduser', function($iduser) {
   
	User::verifyLogin();
	
	$user = new User();
	
	$user->get((int)$iduser);
	
	$page = new PageAdmin();
	
	$page->setTpl("users-update",array(
		"user" => $user->getValues()
	));
	
});
//POST -> CREATE
$app->post('/admin/users/create', function() {
	
	User::verifyLogin();
	
	$user = new User();
	
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	
	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);
	
	$user->setData($_POST);
	
	$user->save();
	
	header("Location: /admin/users");
	exit;
	
});
//POST -> UPDATE
$app->post('/admin/users/:iduser', function($iduser) {
	
	User::verifyLogin();
	
	$user = new User();
	
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	
	$user->get((int)$iduser);
	
	$user->setData($_POST);
	
	$user->update();
	
	header("Location: /admin/users");
	exit;
	
});
$app->run();

 ?>