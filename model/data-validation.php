<?php
/**
 * Created by PhpStorm.
 * User: Brandon Skar
 * Date: 1/22/2019
 * Time: 11:48 PM
 */

$phone = str_replace('(', '', $phone);
$phone = str_replace(')', '', $phone);
$phone = str_replace('-', '', $phone);
function validFirstName($name) {
    return ctype_alpha($name);
}

function validLastName($name) {
    return ctype_alpha($name);
}

function validAge($age) {
    return ctype_alnum($age) && ($age >= 18);
}

function validPhone($phone) {
    //Must be a a numeric string of 10 digits
    return (preg_match("/^[0-9]{10}$/", $phone));
}

function validEmail($email) {
    return strpos($email, '@') !== false &&
        strpos(strtolower($email), '.com') !== false;
}

$f3->set('validFname', (validFirstName($fname)));
$f3->set('validLname', validLastName($lname));
$f3->set('validAge', (validAge($age)));
$f3->set('validPhone', (validPhone($phone)));
$f3->set('validEmail', (validEmail($email)));