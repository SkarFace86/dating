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

class Database
{
    /**
     * Connects to the database
     */
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

    /**
     * Inserts member information into the database
     */
    public function insertMember()
    {
        global $dbh;

        $member = $_SESSION['member'];

        //get all the information stored in the member object
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

        //create a comma separated string of the interests
        if((boolean)$premium) {
            //if both indoor and outdoor interests were selected
            if(!empty($member->getInDoorInterests()) && !empty($member->getOutDoorInterests())) {

                $interests = implode(', ', $member->getInDoorInterests()) . ', ' . implode(', ', $member->getOutDoorInterests());
            }
            //if indoor interests were selected and outdoor interests was not selected
            else if(!empty($member->getInDoorInterests())) {
                $interests = implode(', ', $member->getInDoorInterests());
            }
            //if outdoor interests were selected and indoor interests was not selected
            else if(!empty($member->getOutDoorInterests())) {
                $interests = implode(', ', $member->getOutDoorInterests());
            }
        }

        $sql = "INSERT INTO datingMembers
            (fname, lname, age, gender, phone, email, state, seeking, bio, premium, image, interests)
            VALUES(:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :image, :interests)";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
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

        //execute and check for errors
        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    /**
     * Gets all the information from the database to display in a table for the user
     * @return array Returns an array of all the rows and columns in the database
     */
    public function getMembers()
    {
        global $dbh;

        $sql = "SELECT * FROM datingMembers
                ORDER BY lname ASC";

        $statement = $dbh->prepare($sql);

        //execute and check for errors
        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Gets a single users data from the database to display their information to the user
     * @param $id the ID to use as primary key for searching for a specific user
     * @return mixed Returns an array of a single member and all of their information with the given ID
     */
    public function getMember($id)
    {
        global $dbh;

        $sql = "SELECT * FROM datingMembers
                WHERE member_id = :id";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_STR);

        //execute and check for errors
        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}