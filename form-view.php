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
$emailValidated = false;
$streetnumberValidated = false;
$zipcodeValidated = false;

// Validate email
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
        $streetnumber = $_POST["streetnumber"];
        if (is_numeric($_POST["streetnumber"]) !== true) {
            $streetnumberErr = 'Numbers are only allowed in this field';
        } else {
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
        $zipcode = $_POST["zipcode"];
        if (is_numeric($_POST["zipcode"]) !== true) {
            $zipcodeErr = 'Numbers are only allowed in this field';
        } else {
            $zipcodeValidated = true;
        }

    }
    if ($_POST["products"] == null) {
        echo "<span class='alert-danger'>You have not selected any of our tasty food and drinks please select some and try to submit again</span>";
    }

    $test = false;
    if ($_POST["products"] !== null && $email !== null && $emailValidated = true && $street !== null && $streetnumber !== null && $streetnumberValidated = true && $city !== null && $zipcode !==null && $zipcodeValidated = true) {
        echo "<span class='alert-success'>Your order has been processed and sent!</span><br>";
        echo "<span class='alert-success'>You have chosen ";
        echo "$_POST[delivery]";
        echo " delivery and your product shall arrive in around <span id='time'>";
        if ($_POST['delivery'] == "express") {
            echo "45 minutes ";
            showDeliveryTime("+45 minutes");
            echo '<br>';
        } else {
            echo "2 hours.";
            showDeliveryTime("+2 hours");
            echo '<br>';
        }
        echo "</span></span>";
        $test = true;
        $totalPurchase = 0;
        $_COOKIE["email"] = $email;
        $_COOKIE["street"] = $street;
        $_COOKIE["streetnumber"] = $streetnumber;
        $_COOKIE["city"] = $city;
        $_COOKIE["zipcode"] = $zipcode;

        $totalValue = 0;
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, '?food=1') == true) {
            foreach ($_POST["products"] as $i => $selected) {
                $totalValue += $food[$i]["price"];
            }
        }
        if (strpos($url, '?food=0') == true) {
            foreach ($_POST["products"] as $i => $selected) {
                $totalValue += $drinks[$i]["price"];
            }
        }
        $_SESSION['totalValue'] += $totalValue;

        function emailMessage (){

        }
        $subject = "You have just ordered from the Personal Ham Processors!  We thank you for your purchase!";
        $closingStatement = "You have just ordered a total of &euro;$totalValue";
       // var_dump(mail ( $email , $subject , $message));
        echo $subject;
        echo '<br><br>';
        echo "Email: $email <br>";
        echo "Street name: $street <br>";
        echo "Street number: $streetnumber <br>";
        echo "City: $city <br>";
        echo "Zipcode: $zipcode <br><br>";
        echo "You have ordered: <br>";
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        if (strpos($url, '?food=1') == true) {
            foreach ($_POST["products"] as $i => $selected) {
                echo $food[$i]["name"];
                echo ' &euro;';
                echo $food[$i]["price"];
                echo "<br>";
            }
        }
        if (strpos($url, '?food=0') == true) {
            foreach ($_POST["products"] as $i => $selected) {
                echo $drinks[$i]["name"];
                echo ' &euro;';
                echo $drinks[$i]["price"];
                echo "<br>";
            }
        }
        echo '<br>';
        echo "$closingStatement.";
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
                    if ($test == true) {
                        echo "$_COOKIE[street]";
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
                        echo "$_COOKIE[streetnumber]";
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
                        echo "$_COOKIE[city]";
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
                        echo "$_COOKIE[zipcode]";
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
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
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

    <footer>You already ordered <strong>&euro; <?php echo $_SESSION['totalValue'] ?></strong> in food and drinks.</footer>
</div>
<script src='./assets/js/script.js'></script>
<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>