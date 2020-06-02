<?php 

require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index",$data = array(1,2));
});

$app->run();

 ?>