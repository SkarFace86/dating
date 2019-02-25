<?php
/**
 * Created by PhpStorm.
 * User: Brandon Skar
 * Date: 1/22/2019
 * Time: 11:48 PM
 */
//Turn on error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

//validate the first name entered by the user
function validFirstName($name) {
    return ctype_alpha($name);
}

//validate the last name entered by the user
function validLastName($name) {
    return ctype_alpha($name);
}

//validate the age entered by the user
function validAge($age) {
    return ctype_alnum($age) && ($age >= 18);
}

//validate the phone number entered by the user
function validPhone($phone) {
    $phone = str_replace('(', '', $phone);
    $phone = str_replace(')', '', $phone);
    $phone = str_replace('-', '', $phone);
    //Must be a a numeric string of 10 digits
    return (preg_match("/^[0-9]{10}$/", $phone));
}

//validate the email entered by the user
function validEmail($email) {
    return strpos($email, '@') !== false &&
        strpos(strtolower($email), '.com') !== false;
}

//validate the indoor activities entered by the user
function validIndoor($interests) {
    foreach($interests as $interest) {
        if(!in_array($interest, $_SESSION['indoor'])) {
            return false;
        }
    }
    return true;
}

//validate the outdoor activities entered by the user
function validOutdoor($interests) {
    foreach($interests as $interest) {
        if(!in_array($interest, $_SESSION['outdoor'])) {
            return false;
        }
    }
    return true;
}