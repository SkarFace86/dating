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
    //get the POST information
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $premium = $_POST['premium'];

    //if POST is not empty check that the information given is valid
    if(!empty($_POST)) {
        if(validFirstName($fname) && validLastName($lname) &&
            validAge($age) && validPhone($phone)) {

            //set up phone to display as (555)555-5555 format
            $phone = changePhoneFormat($phone);

            //check if the member selected to be premium member or not and create a member object
            if($premium == 'premium') {
                $member = new PremiumMember($fname, $lname,
                        $age, $gender, $phone);
            } else {
                $member = new Member($fname, $lname,
                    $age, $gender, $phone);
            }

            //store the member object in a session
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

    //check if the POST array is not empty and validate the email
    if(!empty($_POST) && validEmail($_POST['email'])) {
        //get the information from the POST array
        $email = strtolower($_POST['email']);
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $bio = $_POST['bio'];

        //set member object variables with the information given
        $member->setEmail($email);
        $member->setState($state);
        $member->setSeeking($seeking);
        $member->setBio($bio);

        //if the user is premium send them to interests page
        //otherwise send them to the summary page
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

    //check if the submit button was pressed
    if(isset($_POST['submit'])) {

        //if both indoor and outdoor interests were selected
        if(!empty($_POST['indoor']) && !empty($_POST['outdoor'])) {

            //make sure there is no code injection here
            if(!validIndoor($_POST['indoor']) && !validOutdoor($_POST['outdoor'])) {
                $f3->reroute('interests');
            }

            //set the interests to the member object variable
            $member->setIndoorInterests($_POST['indoor']);
            $member->setOutdoorInterests($_POST['outdoor']);
        }

        //if only indoor interests was selected
        else if(!empty($_POST['indoor'])) {

            //make sure no code injection happened
            if(!validIndoor($_POST['indoor'])) {
                $f3->reroute('interests');
            }

            //set the indoor variable on the member object
            $member->setIndoorInterests($_POST['indoor']);
        }

        //if only outdoor interests was selected
        else if(!empty($_POST['outdoor'])) {

            //make sure no code injection happened
            if(!validOutdoor($_POST['outdoor'])) {
                $f3->reroute('interests');
            }

            //set the outdoor variable on the member object
            $member->setOutdoorInterests($_POST['outdoor']);
        }

        $f3->reroute('summary');
    }

    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3) {
    $f3->set('title', 'Summary');

    //connect to the database
    $db = new Database();
    $db->connect();

    //insert the members information into the database
    $db->insertMember();

    $template = new Template();
    echo $template->render('views/summary.html');
});

$f3->route('GET|POST /admin', function($f3) {
    $f3->set('title', 'Admin');

    //connect to the database
    $db = new Database();
    $db->connect();

    //get all the members information from the database
    $results = $db->getMembers();
    $f3->set('results', $results);

    $template = new Template();
    echo $template->render('views/admin.html');
});

$f3->route('GET|POST /member-profile/@id', function($f3, $params) {
    $f3->set('title', 'Member Profile');

    //get the member id to search for in the database
    $memberId = $params['id'];

    //connect to the database
    $db = new Database();
    $db->connect();

    //get the specific member with the given member_id
    $result = $db->getMember($memberId);
    $f3->set('result', $result);

    $template = new Template();
    echo $template->render('views/member-profile.html');
});

//Run fat-free
$f3->run();
