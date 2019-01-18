<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 * Date: 1/15/2019
 * Initiate fat free
 */
session_start();

$fname = "";
$lname = "";
$age = "";
$gender = "";
$phone = "";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include('include/styles.html'); ?>
    <title>Personal Information</title>
</head>
<body>
<?php include('include/navbar.html'); ?>
<div class="container col-lg-8 col-md-12 border border-info rounded p-4 mt-4">
    <div class="row col-12">
        <h1>Personal Information</h1>
    </div>
        <hr class="alert-secondary">
    <div class="row">
        <div class="col-7">
            <form>
                <label class="font-weight-bold w-100">First Name
                    <br>
                    <input type="text" name="fname" class="w-100"
                        <?php echo "value='$name'"; ?> >
                </label> <!-- First Name -->
                <br>
                <label class="font-weight-bold w-100">Last Name
                    <br>
                    <input type="text" name="lname" class="w-100"
                        <?php echo "value='$lname'"; ?> >
                </label> <!-- Last Name -->
                <br>
                <label class="font-weight-bold w-100">Age
                    <br>
                    <input type="text" name="age" class="w-100"
                        <?php echo "value='$age'"; ?> >
                </label> <!-- Age -->
                <br>
                <label name="gender"><span class="font-weight-bold">Gender</span>
                    <br>
                    <input type="radio" value="male" name="method"
                           class="font-weight-normal">&nbspMale
                    &nbsp&nbsp
                    <input type="radio" value="female" name="method">&nbspFemale
                </label> <!-- Gender -->
                <label class="font-weight-bold w-100">Phone
                    <br>
                    <input type="text" name="phone" class="w-100"
                           placeholder="(XXX)-XXX-XXXX"
                        <?php echo "value='$phone'"; ?> >
                </label>
        </div> <!-- col-7 -->
        <div class="col-5 tos bg-secondary">

        </div>
    </div> <!-- row col-12 -->
    <div class="row">
        <div class="col-10"></div> <!-- empty space -->
        <div class="col-2 align-center">
            <input type="submit" value="Next >" method="POST" action=""
                   class="btn btn-primary mx-5">
        </div>
    </div> <!-- empty space -->
            </form> <!-- end form -->
</div> <!-- div class container -->



<?php include('include/scripts.html'); ?>
</body>
</html>