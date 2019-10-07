<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$info = [];
// Variable to check


// Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "$_POST[email] is NOT a valid email address";
    } else {
        $email = $_POST['email'];
    }

    if (empty($_POST["street"])) {
            $streetErr = "Street is required";
    } else {
            $street = $_POST["street"];
        }
    if (empty($_POST["streetnumber"])) {
        $streetnumberErr = "Street number is required";
    } else {
        $streetnumber = $_POST["streetnumber"];
        if (is_numeric($_POST["streetnumber"]) !== true) {
            $streetnumberErr = 'Numbers are only allowed in this field';
        }
    }
    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
        $city = $_POST["city"];
    }
    if (empty($_POST["zipcode"])) {
        $zipcodeErr = "Zipcode is required";
    } else {
        $zipcode = $_POST["zipcode"];
        if (is_numeric($_POST["zipcode"]) !== true) {
            $zipcodeErr = 'Numbers are only allowed in this field';
        }

    }
    $test = false;
    if ($email !== null && $street !== null && $streetnumber !== null && $city !== null && $zipcode !==null) {
        echo "<span class='alert-success'>Your order has been processed and sent!</span>";
        echo "<span class='alert-success'>You have chosen ";
        echo "$_POST[delivery]";
        echo " express delivery and you're product shall arrive in around <span id='time'>";
        if ($_POST[delivery] == "express") {
            echo "45 minutes.";
        } else {
            echo "2 hours.";
        }
        echo "</span></span>";
        $test = true;
        $_SESSION["email"] = $email;
        $_SESSION["street"] = $street;
        $_SESSION["streetnumber"] = $streetnumber;
        $_SESSION["city"] = $city;
        $_SESSION["zipcode"] = $zipcode;


    }

}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Order food & drinks</title>
</head>
<body>
<div class="container">
    <h1>Order food in restaurant "the Personal Ham Processors"</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?food=1">Order food</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=0">Order drinks</a>
            </li>
        </ul>
    </nav>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" class="form-control" value=<?php
                if ($test == true) {
                echo "$_SESSION[email]";
                } else {
                    echo "$email";
                }?>>
                <?php echo "<span class='alert-danger'>";
                      echo  $emailErr;
                      echo "</span>"; ?>
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street" class="form-control" value=<?php
                    if ($test == true) {
                        echo "$_SESSION[street]";
                    } else {
                        echo "$street";
                    }?>>
                    <?php echo "<span class='alert-danger'>";
                    echo  $streetErr;
                    echo "</span>"; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="text" id="streetnumber" name="streetnumber" class="form-control" value=<?php
                    if ($test == true) {
                        echo "$_SESSION[streetnumber]";
                    } else {
                        echo "$streetnumber";
                    }?>>
                    <?php echo "<span class='alert-danger'>";
                    echo  $streetnumberErr;
                    echo "</span>"; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" class="form-control" value=<?php
                    if ($test == true) {
                        echo "$_SESSION[city]";
                    } else {
                        echo "$city";
                    }?>>
                    <?php echo "<span class='alert-danger'>";
                    echo  $cityErr;
                    echo "</span>"; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode" class="form-control" value=<?php
                    if ($test == true) {
                        echo "$_SESSION[zipcode]";
                    } else {
                        echo "$zipcode";
                    }?>>
                    <?php echo "<span class='alert-danger'>";
                    echo  $zipcodeErr;
                    echo "</span>"; ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php   $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            if (strpos($url,'?food=1') == true) {
                $products = $food;
            } else if (strpos($url,'?food=0') == true) {
               $products = $drinks;
            } else {
                $products = $food;
            }
            foreach ($products AS $i => $product): ?>
                <label>
                    <input type="checkbox" value="1" name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?> -
                    &euro; <?php echo number_format($product['price'], 2) ?></label><br />
            <?php endforeach; ?>
            <label>Delivery Options
                <select name="delivery" class="form-control" id="delivery">
                    <option value="express">Express Delivery (45 minutes)</option>
                    <option value="normal">Normal Delivery (2 hours)</option>
                </select>
            </label>
        </fieldset>
        <button type="submit" class="btn btn-primary" id="submit">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php echo $totalValue ?></strong> in food and drinks.</footer>
</div>
<script src='./assets/js/script.js'></script>
<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>