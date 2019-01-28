<?php
/**
 * Created by PhpStorm.
 * User: Brandon Skar
 * Date: 1/22/2019
 * Time: 11:48 PM
 */

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];

$validName = false;
$validLname = false;
$validAge = false;
$validPhone = false;

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
    return (preg_match("/^[0-9]{10}$/", $phone));
}
$f3->set('validFname', (validFirstName($fname)));
$f3->set('validLname', validLastName($lname));
$f3->set('validAge', (validAge($age)));
$f3->set('validPhone', (validPhone($phone)));