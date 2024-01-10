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

// Update product
if (isset($_POST["update"])) {
    $productId = $_POST["UProductId"];
    $UProductName = $_POST["UProductName"];
    $UProductDesc = $_POST["UProductDesc"];
    $UProductPrice = $_POST["UProductPrice"];
    $UProductBrand = $_POST["UProductBrand"];
    $UProductQty = $_POST["UProductQty"];

    // Check if the Brand Name exists
    $checkBrandQuery = "SELECT Brand_Id FROM brand WHERE UPPER(Brand_Name) = UPPER('$UProductBrand')";
    $brandResult = mysqli_query($conn, $checkBrandQuery);

    if ($brandResult !== false) {
        // Check if the query was successful
        if ($brandData = mysqli_fetch_assoc($brandResult)) {
            $brandId = $brandData["Brand_Id"];
        } else {
            // Brand Name does not exist, insert the new brand
            $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('$UProductBrand')";
            mysqli_query($conn, $insertBrandQuery);
            
            // Retrieve the brand ID of the newly inserted brand
            $brandId = mysqli_insert_id($conn);
        }

        // Update product details with the retrieved brand ID
        $updateQuery = "UPDATE product SET Prod_Name='$UProductName', Prod_Desc='$UProductDesc', Prod_Price='$UProductPrice', Brand_Id='$brandId' WHERE Prod_Id='$productId'";
        mysqli_query($conn, $updateQuery);

        // Update inventory details
        $updateInventoryQuery = "UPDATE inventory SET Inv_Item_Qty='$UProductQty' WHERE Prod_id='$productId'";
        mysqli_query($conn, $updateInventoryQuery);

        echo "<script>alert('Product updated successfully');</script>";
    } else {
        // Error in the query, handle accordingly
        echo "<script>alert('Error checking Brand Name');</script>";
    }
}
// Display Product Details
$getallprod = "SELECT * FROM product";
$result = mysqli_query($conn, $getallprod);

$getinv_item_qty = "SELECT inventory.inv_item_qty
                    FROM inventory
                    INNER JOIN product ON inventory.prod_id = product.prod_id";

$inv_result = mysqli_query($conn, $getinv_item_qty);

$getbrandname = "SELECT product.prod_id, product.prod_name, brand.brand_name
                 FROM product
                 INNER JOIN brand ON product.brand_id = brand.brand_id";


$brand_result = mysqli_query($conn, $getbrandname);



// Now you can use $inv_result to fetch the inv_item_qty values.


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

    <!-- Update Product Form -->
    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Update Product</h2>

                <label for="UProductId">Product ID:</label>
                <input type="text" id="UProductId" name="UProductId" required><br><br>

                <label for="UProductName">Product Name:</label>
                <input type="text" id="UProductName" name="UProductName" required><br><br>

                <label for="UProductDesc">Product Description:</label>
                <input type="text" id="UProductDesc" name="UProductDesc" required><br><br>

                <label for="UProductPrice">Product Price:</label>
                <input type="number" id="UProductPrice" name="UProductPrice" required><br><br>

                <label for="UProductBrand">Brand:</label>
                <input type="text" id="UProductBrand" name="UProductBrand" required><br><br>

                <label for="UProductQty">Item Quantity:</label>
                <input type="number" id="UProductQty" name="UProductQty" required><br><br>

                <input type="submit" name="update" value="Update">

        <!-- Display Product Details -->
        <div class="content">
            <div class="container">
                <h2>Product Details</h2>

                <table border="5">
                    <tr>
                        <td>Product ID</td>
                        <td>Product Name</td>
                        <td>Product Description</td>
                        <td>Brand</td>
                        <td>Product Price</td>
                        <td>Item Quantity</td>
                    </tr>
        <tr>
        <?php
while ($row = mysqli_fetch_assoc($result)) {
    // Fetch brand name
    $brand_query = "SELECT brand_name FROM brand WHERE brand_id = " . $row['brand_id'];
    $brand_result = mysqli_query($conn, $brand_query);
    $brand_data = mysqli_fetch_assoc($brand_result);

    // Fetch inventory item quantity
    $inv_query = "SELECT inv_item_qty FROM inventory WHERE prod_id = " . $row['prod_id'];
    $inv_result = mysqli_query($conn, $inv_query);
    $inv_data = mysqli_fetch_assoc($inv_result);
    ?>
    <tr>
        <td><?php echo $row['prod_id']; ?></td>
        <td><?php echo $row['prod_name']; ?></td>
        <td><?php echo $row['prod_desc']; ?></td>
        <td><?php echo $brand_data['brand_name']; ?></td>
        <td><?php echo $row['prod_price']; ?></td>
        <td><?php echo $inv_data['inv_item_qty']; ?></td>
    </tr>
<?php
}
?>
                    
            </div>
        </div>
    </form>
    <form action="/" method="post"></form>
    <?php include "scripts.php"; ?>
</body>
</html>
