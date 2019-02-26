<?php
/**
 * Created by PhpStorm.
 * User: SkarFace
 * Date: 2/23/2019
 * Time: 4:28 PM
 */

//Turn on error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

//CREATE TABLE datingMembers
//(member_id INT AUTO_INCREMENT PRIMARY KEY,
// fname VARCHAR(40),
// lname VARCHAR(40),
// age INT,
// gender VARCHAR(10),
// phone VARCHAR(10),
// email VARCHAR(40),
// state VARCHAR(20),
// seeking VARCHAR(10),
// bio VARCHAR(1000),
// premium TINYINT,
// image VARCHAR(100),
// interests VARCHAR(1000));

class Database {
    public function connect()
    {
        require_once('/home/bskargre/config.php');

        try {
            //instantiate a database object
            $GLOBALS['dbh'] = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertMember()
    {
        global $dbh;

        $member = $_SESSION['member'];

        $fname = $member->getFname();
        $lname= $member->getLname();
        $age = $member->getAge();
        $gender = $member->getGender();
        $phone = $member->getPhone();
        $email = $member->getEmail();
        $state = $member->getState();
        $seeking = $member->getSeeking();
        $bio = $member->getBio();
        $image = null;
        $premium = get_class($member) == 'PremiumMember' ? 1 : 0;
        $interests = null;

        if((boolean)$premium) {
            if(!empty($member->getInDoorInterests()) && !empty($member->getOutDoorInterests())) {

                $interests = implode(', ', $member->getInDoorInterests()) . ', ' . implode(', ', $member->getOutDoorInterests());
            }
            else if(!empty($member->getInDoorInterests())) {
                $interests = implode(', ', $member->getInDoorInterests());
            }
            else if(!empty($member->getOutDoorInterests())) {
                $interests = implode(', ', $member->getOutDoorInterests());
            }
        }

        $sql = "INSERT INTO datingMembers
            (fname, lname, age, gender, phone, email, state, seeking, bio, premium, image, interests)
            VALUES(:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :image, :interests)";

        $statement = $dbh->prepare($sql);
        $statement->bindValue(':fname', $fname, PDO::PARAM_STR);
        $statement->bindValue(':lname', $lname, PDO::PARAM_STR);
        $statement->bindValue(':age', $age, PDO::PARAM_STR);
        $statement->bindValue(':gender', $gender, PDO::PARAM_STR);
        $statement->bindValue(':phone', $phone, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':state', $state, PDO::PARAM_STR);
        $statement->bindValue(':seeking', $seeking, PDO::PARAM_STR);
        $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
        $statement->bindValue(':premium', $premium, PDO::PARAM_STR);
        $statement->bindValue(':image', $image, PDO::PARAM_STR);
        $statement->bindValue(':interests', $interests, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

    }

    public function getMembers()
    {
        global $dbh;

        $sql = "SELECT * FROM datingMembers";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMember($id)
    {

    }
}