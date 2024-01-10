<?php


 $serverName = "localhost";
 $username = "root";
 $password = "";
 $dbname = "mimimi";

$conn = new mysqli($serverName,$username, $password, $dbname);

//  if ($conn->connect_error) {
//      echo "Connection failed: " . $conn->connect_error;
//  }
//  else {
//      echo "Connection suck myd: ";
//  }




$type = "";
$email = "";
$phone = "";



if (isset($_POST["submit"])) {
    $type = $_POST["sup_type"];
    $email = $_POST["sup_email"];
    $phone = $_POST["sup_phone"];

    $query = "INSERT INTO supplier (sup_type, sup_email, sup_phone) VALUES('$type', '$email', '$phone')";
    mysqli_query($conn, $query);

    echo "<script>alert('Supplier is added');</script>";
}

?>
<html>
<head>
    <?php include "Style.php"; ?>
</head>
<body>
    <?php include "header.php"; ?>

<div class = "content">
        <div class = " container">
        <h1> Mimi's Pet Shop </h1>
        <p>Mahayahay, Gabi, Cordova</p>
        <p>mimispetcorner@gmail.com</p>
    </div>
    </div>
    <form action="" method="post">
    <div class="content">
        <div class="container">
            <h2>Add Supplier</h2>
            <label for="sup_type">Supplier Type:</label>
            <select class="" name="sup_type" required>
                <option value="" selected hidden>--SELECT--</option>
                <option value="Dogs">Dogs</option>
                <option value="Cats">Cats</option>
                <option value="Accessories">Accessories</option>
                <option value="Other">Other</option>
            </select>
            <label for="sup_emaillbl">Supplier Email:</label>
            <input type="text" id="sup_email" name="sup_email" required><br><br>
            <label for="sup_phonelbl">Supplier Phone:</label>
            <input type="number" id="sup_phone" name="sup_phone" required><br><br>
            <input type="submit" name="submit" value="Add">
            <input type="submit" name="delete" value="Delete">
            <input type="submit" name="update" value="Update">
        </div>
    </div>
</form>
    </div>

<form action="/" method="post">
  
</form>
<?php include "scripts.php"; ?>
</body>
</html>