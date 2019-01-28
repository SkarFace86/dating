<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 * Date: 1/9/2019
 * Time: 1:30 PM
 * Initiate fat free
 */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require fat-free
require_once('vendor/autoload.php');
session_start();
//Create an instance of the Base class
$f3 = Base::instance();

//Turn of fat free error reporting
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /', function() {
    $view = new View();
    echo $view->render('views/home.html');
});

//Personal information route
$f3->route('POST /personal-info', function($f3) {
    if(!empty($_POST)) {
        include('model/personal-info-validation.php');
        if($validName) {
            $f3->reroute('profile');
        }
    }
    print_r($_POST);

    $template = new Template();
    echo $template->render('views/personal-info.html');
});

$f3->route('GET /profile', function($f3) {
    $template = new Template();
    echo $template->render('views/profile.html');
});
//Run fat-free
$f3->run();
