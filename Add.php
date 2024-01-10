<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbname = "mimimi";

$conn = new mysqli($serverName, $username, $password, $dbname);

$pname = "";
$pdesc = "";
$pprice = "";
$brand = "";
$qty = "";

// Function to retrieve product details by ID
function getProductDetails($conn, $productId) {
    $query = "SELECT * FROM product WHERE Prod_Id = '$productId'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Add new product
if (isset($_POST["submit"])) {
    $pname = $_POST["ProdName"];
    $pdesc = $_POST["ProdDesc"];
    $pprice = $_POST["ProdPrice"];
    $brand = $_POST["Brand"];
    $qty = $_POST["InvItemQty"];

    // Check if the brand already exists
    $checkBrandQuery = "SELECT * FROM brand WHERE UPPER(Brand_Name) = UPPER('$brand')";
    $brandResult = mysqli_query($conn, $checkBrandQuery);

    if ($brandResult !== false) {
        // Check if the query was successful
        if (mysqli_num_rows($brandResult) > 0) {
            // Brand already exists, retrieve the existing brand ID
            $brandData = mysqli_fetch_assoc($brandResult);
            $brandId = $brandData["brand_id"];
        } else {
            // Brand doesn't exist, insert a new brand and get the brand ID
            $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('$brand')";
            mysqli_query($conn, $insertBrandQuery);
            $brandId = mysqli_insert_id($conn);
        }

        // Step 3: Insert product into the product table with the retrieved or new brand ID
        $query2 = "INSERT INTO product (Prod_Name, Prod_Desc, Prod_Price, Brand_Id) VALUES ('$pname', '$pdesc', '$pprice', '$brandId')";
        mysqli_query($conn, $query2);

        // Step 4: Insert data into the inventory table
        $query3 = "INSERT INTO inventory (Inv_Item_Qty, Prod_id) SELECT '$qty', Prod_Id FROM product WHERE Prod_Name = '$pname'";
        mysqli_query($conn, $query3);

        echo "<script>alert('Product is added');</script>";
    } else {
        // Error in the query, handle accordingly
        echo "<script>alert('Error checking Brand');</script>";
    }
}


// Delete product
if (isset($_POST["delete"])) {
    $productIdToDelete = $_POST["ProductIdToDelete"];

    // Delete product from product table
    $deleteProductQuery = "DELETE FROM product WHERE Prod_Id = '$productIdToDelete'";
    mysqli_query($conn, $deleteProductQuery);

    // Delete product from inventory table
    $deleteInventoryQuery = "DELETE FROM inventory WHERE Prod_id = '$productIdToDelete'";
    mysqli_query($conn, $deleteInventoryQuery);

    echo "<script>alert('Product is deleted');</script>";
}



?>

<html>
<head>
    <?php include "Style.php"; ?>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
        </div>
    </div>

    <!-- Add Product Form -->
    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Add Product</h2>
                <label for="ProdName">Product Name:</label>
                <input type="text" id="ProdName" name="ProdName" required><br><br>

                <label for="ProdDesc">Product Description:</label>
                <input type="text" id="ProdDesc" name="ProdDesc" required><br><br>

                <label for="ProdPrice">Product Price:</label>
                <input type="number" id="ProdPrice" name="ProdPrice" required><br><br>

                <label for="Brand">Brand:</label>
                <input type="text" id="Brand" name="Brand" required><br><br>

                <label for="InvItemQty">Item Quantity:</label>
                <input type="number" id="InvItemQty" name="InvItemQty" required><br><br>

                <input type="submit" name="submit" value="Add">
            </div>
        </div>
    </form>

    <!-- Delete Product Form -->
    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Delete Product</h2>
                <label for="ProductIdToDelete">Product ID to Delete:</label>
                <input type="text" id="ProductIdToDelete" name="ProductIdToDelete" required><br><br>

                <input type="submit" name="delete" value="Delete">
            </div>
        </div>
    </form>

    <form action="/" method="post"></form>
    <?php include "scripts.php"; ?>
</body>
</html>
