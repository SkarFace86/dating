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
$f3->route('GET /', function($f3) {
    $f3->set('title', 'Your Forever Finder');
    $template = new Template();
    echo $template->render('views/home.html');
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
    $premium = $_POST['premium'];
    if(!empty($_POST)) {
        if(validFirstName($fname) && validLastName($lname) &&
            validAge($age) && validPhone($phone)) {
            if($premium == 'premium') {
                $member = new PremiumMember($fname, $lname,
                        $age, $gender, $phone);
            } else {
                $member = new Member($fname, $lname,
                    $age, $gender, $phone);
            }
            $_SESSION['member'] = $member;
            $f3->reroute('profile');
        }
    }
    $template = new Template();
    echo $template->render('views/personal-info.html');
});

$f3->route('GET|POST /profile', function($f3) {
    $f3->set('title', 'Profile');
    $member = $_SESSION['member'];
    if(!empty($_POST) && validEmail($_POST['email'])) {
        $email = strtolower($_POST['email']);
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $bio = $_POST['bio'];
        $member->setEmail($email);
        $member->setState($state);
        $member->setSeeking($seeking);
        $member->setBio($bio);
        if(get_class($member) == 'PremiumMember') {
            $f3->reroute('interests');
        } else {
            $f3->reroute('summary');
        }
    }
    include('include/states.php');
    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests', function($f3) {
    $f3->set('title', 'Interests');
    $member = $_SESSION['member'];
    $member->setIndoorInterests(array());
    $member->setOutdoorInterests(array());
    include('include/interests.php');
    if(isset($_POST['submit'])) {
        if(!empty($_POST['indoor']) && !empty($_POST['outdoor'])) {
            if(!validIndoor($_POST['indoor']) && !validOutdoor($_POST['outdoor'])) {
                $f3->reroute('interests');
            }

            $member->setIndoorInterests($_POST['indoor']);
            $member->setOutdoorInterests($_POST['outdoor']);
        }
        else if(!empty($_POST['indoor'])) {
            if(!validIndoor($_POST['indoor'])) {
                $f3->reroute('interests');
            }

            $member->setIndoorInterests($_POST['indoor']);
        }
        else if(!empty($_POST['outdoor'])) {
            if(!validOutdoor($_POST['outdoor'])) {
                $f3->reroute('interests');
            }

            $member->setOutdoorInterests($_POST['outdoor']);
        }

        $f3->reroute('summary');
    }
    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3) {
    //connect to the database
    $db = new Database();
    $db->connect();
    $db->insertMember();

    $f3->set('title', 'Summary');
    $template = new Template();
    echo $template->render('views/summary.html');
});

//Run fat-free
$f3->run();
