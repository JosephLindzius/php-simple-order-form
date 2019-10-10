<?php

// https://github.com/becodeorg/atw-lamarr-2-13/tree/master/2.The-Hills/php/3.simple-order-form

function showDeliveryTime ($_time) {
    $time = strtotime($_time);
    $datetime = new DateTime();
    $datetime->setTimestamp($time);
    $format = $datetime->format('H:i');
    echo "or around $format";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $info = [];
    $random = 0;
    $emailValidated = false;
    $streetnumberValidated = false;
    $zipcodeValidated = false;

// Validating email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "$_POST[email] is NOT a valid email address";
    } else {
        $email = $_POST['email'];
        $emailValidated = true;
    }

    if (empty($_POST["street"])) {
            $streetErr = "Street is required";
    } else {
            $street = $_POST["street"];
        }

    if (empty($_POST["streetnumber"])) {
        $streetnumberErr = "Street number is required";
    } else {
        if (is_numeric($_POST["streetnumber"]) !== true) {
            $streetnumberErr = 'Numbers are only allowed in this field';
        } else {
            $streetnumber = $_POST["streetnumber"];
            $streetnumberValidated = true;
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
        if (is_numeric($_POST["zipcode"]) !== true) {
            $zipcodeErr = 'Numbers are only allowed in this field';
        } else {
            $zipcode = $_POST["zipcode"];
            $zipcodeValidated = true;
        }

    }
    if ($_POST["products"] == null) {
        echo "<span class='alert-danger'>You have not selected any of our tasty food and drinks please select some and try to submit again</span><br>";
    }

    if ($_POST["products"] !== null && $email !== null && $emailValidated = true && $street !== null && $streetnumber !== null && $streetnumberValidated = true && $city !== null && $zipcode !==null && $zipcodeValidated = true) {
        //setting up all my session variables that will be saved for the form
        $random = rand(0, 100000000);
        $_SESSION["email"] = $email;
        $_SESSION["street"] = $street;
        $_SESSION["streetnumber"] = $streetnumber;
        $_SESSION["city"] = $city;
        $_SESSION["zipcode"] = $zipcode;
        $_SESSION["random"] = $random;
        $totalValue = 0;

        if ($_GET['food'] == 1) {
            foreach ($_POST["products"] as $i => $selected) {
                for ($itemNumber = 0; $itemNumber < $selected; $itemNumber++) {
                    $totalValue += $food[$i]["price"];
                }
            }
        }

        if ($_GET['food'] == 0) {
            foreach ($_POST["products"] as $i => $selected) {
                for ($itemNumber = 0; $itemNumber < $selected; $itemNumber++) {
                    $totalValue += $drinks[$i]["price"];
                }
            }
        }

        //Now updating the cookie to the last value.
        $_COOKIE['totalValue'] += $totalValue;
        $cookie = $_COOKIE['totalValue'];
        setcookie("totalValue", $cookie, time() + 60*60*24*365);

        $closingStatement = "You have just ordered a total of </em>&euro;$totalValue</em>";

        $to      = "$email";
        $subject = "You have just ordered from the Personal Ham Processors! We thank you for your purchase!";
        $message = '<html><body>';
        $message .= "<h1>You have just ordered from the Personal Ham Processors! We thank you for your purchase!</h1>";
        $message .=  '<br>';
        $message .= "Email: <em>$_SESSION[email]</em> <br>";
        $message .= "Street name: </em>$street</em> <br>";
        $message .= "Street number: </em>$streetnumber</em> <br>";
        $message .= "City: </em>$city</em> <br>";
        $message .= "Zipcode: </em>$zipcode</em> <br><br>";
        $message .= "Order number: </em>#$random</em><br>";
        $message .= "You have ordered: <br><br>";
        if ($_GET['food'] == 1) {
            foreach ($_POST["products"] as $i => $selected) {
                if ($selected > 0) {
                    $message .= "Quantity: $selected<br>";
                    $message .= $food[$i]["name"];
                    $message .= ' &euro;';
                    $message .= $food[$i]["price"];
                    $message .= "<br>";
                }
            }
        }
        if ($_GET['food'] == 0) {
            foreach ($_POST["products"] as $i => $selected) {
                if ($selected > 0) {
                    $message .= "Quantity: $selected<br>";
                    $message .= $drinks[$i]["name"];
                    $message .= ' &euro;';
                    $message .= $drinks[$i]["price"];
                    $message .= "<br>";
                }

            }
        }
        $message .= '<br>';
        $message .= "$closingStatement.";
        $message .= '</body></html>';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: theBigHam@php.com'. "\r\n" .
            'Reply-To: theBigHam@php.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        // to client who for the moment is me
     mail("$to", "$subject", "$message", "$headers");

        $toBoss      = "$email";
        $subjectBoss = "The webshop has recieved a new order!";
        $messageBoss = '<html><body>';
        $messageBoss .= "<h1>You have just received an order!</h1>";
        $messageBoss .=  '<br>';
        $messageBoss .= "Client Infomation:<br><br>";
        $messageBoss .= "Email: <em>$_SESSION[email]</em> <br>";
        $messageBoss .= "Street name: </em>$street</em> <br>";
        $messageBoss .= "Street number: </em>$streetnumber</em> <br>";
        $messageBoss .= "City: </em>$city</em> <br>";
        $messageBoss .= "Zipcode: </em>$zipcode</em> <br><br>";
        $messageBoss .= "Order number: </em>#$random</em><br>";
        $messageBoss .= "The client has ordered: <br><br>";
        if ($_GET['food'] == 1) {
            foreach ($_POST["products"] as $i => $selected) {
                if ($selected > 0) {
                    $messageBoss .= "Quantity: $selected<br>";
                    $messageBoss .= $food[$i]["name"];
                    $messageBoss .= ' &euro;';
                    $messageBoss .= $food[$i]["price"];
                    $messageBoss .= "<br>";
                }
            }
        }
        if ($_GET['food'] == 0) {
            foreach ($_POST["products"] as $i => $selected) {
                if ($selected > 0) {
                    $messageBoss .= "Quantity: $selected<br>";
                    $messageBoss .= $drinks[$i]["name"];
                    $messageBoss .= ' &euro;';
                    $messageBoss .= $drinks[$i]["price"];
                    $messageBoss .= "<br>";
                }
            }
        }
        $messageBoss .= '<br>';
        $messageBoss .= '</body></html>';
        $headersBoss  = 'MIME-Version: 1.0' . "\r\n";
        $headersBoss .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headersBoss .= 'From: theBigHam@php.com'. "\r\n" .
            'Reply-To: theBigHam@php.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
       // to owner who for the moment is me
       mail("$toBoss", "$subjectBoss", "$messageBoss", "$headersBoss");

                //clearing out postdata array
            $_SESSION['postdata'] = $_POST;
           unset($_POST);
           header("Location: ".$_SERVER['REQUEST_URI']);
           exit;


    }

}


if ($_SESSION['postdata'] !== null) {
    echo "<span class='alert-success'>Your order #$_SESSION[random] been processed and sent!</span><br>";
    echo "<span class='alert-success'>You have chosen ";
    echo $_SESSION['postdata']['delivery'];
    echo " delivery and your product shall arrive in around <span id='time'>";
    if ($_SESSION['postdata']['delivery'] == "express") {
        echo "45 minutes ";
        showDeliveryTime("+45 minutes");
        echo '<br>';
    } else {
        echo "2 hours.";
        showDeliveryTime("+2 hours");
        echo '<br>';
    }
    echo "</span></span>";
    unset ($_SESSION['postdata']);
    unset ($_SESSION['random']);
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
                ?>>
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
                    if ($_SESSION["street"] !== null) {
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
                    if ($_SESSION["streetnumber"] !== null) {
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
                    if ($_SESSION["city"] !== null) {
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
                    if ($_SESSION["zipcode"] !== null) {
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
            <?php
            if ($_GET['food'] == 0) {
               $products = $drinks;
            }
            if ($_GET['food'] == 1 || $_GET['food'] == null) {
                $products = $food;
            }
            foreach ($products AS $i => $product): ?>
                <label>
                    <input type="number" value="0" min="0" name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?> -
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

    <footer>You already ordered <strong>&euro; <?php echo number_format((float)$_COOKIE["totalValue"], 2, '.', '') ?></strong> in food and drinks.</footer>
</div>
<script src='./assets/js/script.js'></script>
<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>