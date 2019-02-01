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

require_once('model/data-validation.php');

//Define a default route
$f3->route('GET /', function() {
    $view = new View();
    echo $view->render('views/home.html');
});

//Personal information route
$f3->route('GET|POST /personal-info', function($f3) {
    $f3->set('title', 'Personal Info');
    $_SESSION = array();
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    if(!empty($_POST)) {
        if(validFirstName($fname) && validLastName($lname) &&
            validAge($age) && validPhone($phone)) {
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['age'] = $age;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone'] = $phone;
            $f3->reroute('profile');
        }
    }
    $template = new Template();
    echo $template->render('views/personal-info.html');
});

$f3->route('GET|POST /profile', function($f3) {
    if(!empty($_POST) && validEmail($_POST['email'])) {
        $_SESSION['email'] = strtolower($_POST['email']);
        $_SESSION['state'] = $_POST['state'];
        $_SESSION['seeking'] = $_POST['seeking'];
        $_SESSION['bio'] = $_POST['bio'];
        $f3->reroute('interests');
    }
    if($_POST['seeking'] == 'male') {
        $f3->set('male', "checked='checked'");
        $f3->set('female', "");
    }
    else if($_POST['seeking'] == 'female') {
        $f3->set('male', "");
        $f3->set('female', "checked='checked'");
    }
    include('include/states.php');
    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests', function() {
    include('include/interests.php');
    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3) {
    if(isset($_POST['submit'])) {
        if(!empty($_POST['indoor']) && !empty($_POST['outdoor'])) {
            $interests = array_merge($_POST['indoor'], $_POST['outdoor']);
            $_SESSION['interests'] = implode(", ", $interests);
        }
        else if(!empty($_POST['indoor'])) {
            $_SESSION['interests'] = implode(', ', $_POST['indoor']);
        }
        else if(!empty($_POST['outdoor'])) {
            $_SESSION['interests'] = implode(', ', $_POST['outdoor']);
        }

        $f3->reroute('summary');
    }
    $template = new Template();
    echo $template->render('views/summary.html');
});

//Run fat-free
$f3->run();
