<?php 

// include "Functions.php";


$serverName = "localhost";
$username = "root";
$password = "";
$dbname = "mimimi";

$conn = new mysqli($serverName, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch supplier IDs from the database
$sql = "SELECT sup_id FROM supplier";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Create an array to store the supplier IDs
    $supplierIds = array();

    // Fetch each supplier ID and store it in the array
    while ($row = $result->fetch_assoc()) {
        $supplierIds[] = $row['sup_id'];
    }
} else {
    // Handle the case when there are no supplier IDs
    $supplierIds = array();
}

// Function to insert a new requisition into the requisition table
function insertRequisition($conn, $prod_name, $prod_desc, $brand_name, $prod_qty, $prod_price, $sup_id)
{
    // Check if the supplied $sup_id exists in the supplier table
    $checkSupplierQuery = "SELECT * FROM supplier WHERE sup_id = '$sup_id'";
    $supplierResult = $conn->query($checkSupplierQuery);

    if ($supplierResult->num_rows > 0) {
        // Supplier exists, check if the brand_name already exists
        $checkBrandQuery = "SELECT * FROM brand WHERE UPPER(Brand_Name) = UPPER('$brand_name')";
        $brandResult = $conn->query($checkBrandQuery);

        if ($brandResult !== false) {
            // Check if the query was successful
            if ($brandResult->num_rows > 0) {
                // Brand already exists, retrieve the existing brand ID
                $brandData = $brandResult->fetch_assoc();
                $brandId = $brandData["brand_id"];
            } else {
                // Brand doesn't exist, insert a new brand and get the brand ID
                $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('$brand_name')";
                $conn->query($insertBrandQuery);
                $brandId = $conn->insert_id;
            }

            // Insert requisition with the retrieved or new brand ID
            $sql = "INSERT INTO requisition (req_prod, req_desc, brand_id, req_quantity, req_item_price, sup_id) VALUES ('$prod_name', '$prod_desc', '$brandId', '$prod_qty', '$prod_price', '$sup_id')";

            return $conn->query($sql);
        } else {
            // Error in the query, handle accordingly
            return false;
        }
    } else {
        // Handle the case when the specified $sup_id does not exist in the supplier table
        return false;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Get form data
    $req_prod = $_POST['Req_prod'];
    $req_desc = $_POST['Req_desc'];
    $req_brand_name = $_POST['Req_Brand_Name'];
    $req_prod_qty = $_POST['Req_Prod_Qty'];
    $req_prod_price = $_POST['Req_Prod_Price'];
    $sup_id = $_POST['sup_id'];

    // Insert a new requisition
    $insertRequisitionResult = insertRequisition($conn, $req_prod, $req_desc, $req_brand_name, $req_prod_qty, $req_prod_price, $sup_id);

    if ($insertRequisitionResult) {
        echo "<script>alert('Requisition added successfully');</script>";
    } else {
        echo "<script>alert('Error adding requisition. Invalid supplier ID');</script>";
    }
}

// Function to cancel a requisition from the database
function cancelRequisition($conn, $reqId)
{
    $sql = "DELETE FROM requisition WHERE req_id = '$reqId'";
    return $conn->query($sql);
}

// Function to approve a requisition and update its status
function approveRequisition($conn, $reqId)
{
    $sql = "UPDATE requisition SET req_status = 'APPROVED' WHERE req_id = '$reqId'";
    return $conn->query($sql);
}

// Handle cancel button
if (isset($_POST['cancel'])) {
    $cancelReqId = $_POST['cancel'];
    $cancelResult = cancelRequisition($conn, $cancelReqId);

    if ($cancelResult) {
        echo "<script>alert('Requisition canceled successfully');</script>";
    } else {
        echo "<script>alert('Error canceling requisition');</script>";
    }
}

// Handle approve button
if (isset($_POST['approve'])) {
    $approveReqId = $_POST['approve'];
    $approveResult = approveRequisition($conn, $approveReqId);

    if ($approveResult) {
        echo "<script>alert('Requisition approved successfully');</script>";
    } else {
        echo "<script>alert('Error approving requisition');</script>";
    }
}
//Function to fetch requisition records from the database
function getRequisitions($conn)
{
    $sql = "SELECT * FROM requisition";
    $result = $conn->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch requisition records
$requisitions = getRequisitions($conn);


?>

<html>
<head>
    <?php include "Style.php"; ?>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1>Mimi's Pet Shop</h1>
        </div>
    </div>

    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Request Product</h2><br>
                
                <label for="Req_prodlbl">Product Name:</label>
                <input type="text" id="Req_prod" name="Req_prod" required><br><br>
                <label for="Req_desclbl">Product Description:</label>
                <input type="text" id="Req_desc" name="Req_desc" required><br><br>
                <label for="Req_Brand_Namelbl">Brand Name:</label>
                <input type="text" id="Req_Brand_Name" name="Req_Brand_Name" required><br><br>
                <label for="Req_Prod_Qtylbl">Product Quantity:</label>
                <input type="number" id="Req_Prod_Qty" name="Req_Prod_Qty" required><br><br>
                <label for="Req_Prod_Pricelbl">Product Price:</label>
                <input type="number" id="Req_Prod_Price" name="Req_Prod_Price" required><br><br>

                <label for="sup_id">Supplier Number:</label>
                <select name="sup_id" required>
                    <option value="" selected hidden>--SELECT--</option>
                    <?php
                    // Populate the dropdown list with supplier IDs
                    foreach ($supplierIds as $id) {
                        echo "<option value='$id'>$id</option>";
                    }
                    ?>
                </select><br><br>

                <input type="submit" name="submit" value="Request">
            </div>
        </div>
    </form>

    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Requisition Table</h2>

                
                <table border="1">
                    <tr>
                        <th>Requisition ID</th>
                        <th>Product Name</th>
                        <th>Product Description</th>
                        <th>Brand Name</th>
                        <th>Quantity</th>
                        <th>Supplier ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                    <?php foreach ($requisitions as $req) : ?>
                        <tr>
                            <td><?= $req['req_id']; ?></td>
                            <td><?= $req['req_prod']; ?></td>
                            <td><?= $req['req_desc']; ?></td>

                            <?php
                            // Fetch brand_name from brand table based on brand_id
                            $brandId = $req['brand_id'];
                            $brandNameQuery = "SELECT Brand_Name FROM brand WHERE brand_id = '$brandId'";
                            $brandResult = $conn->query($brandNameQuery);

                            if ($brandResult && $brandResult->num_rows > 0) {
                                $brandData = $brandResult->fetch_assoc();
                                $brandName = $brandData['Brand_Name'];
                            } else {
                                $brandName = "N/A";
                            }
                            ?>

                            <td><?= $brandName; ?></td>
                            <td><?= $req['req_quantity']; ?></td>
                            <td><?= $req['sup_id']; ?></td>
                            <td><?= $req['req_status']; ?></td>
                            <td>
                                <button type="submit" name="approve" value="<?= $req['req_id']; ?>">Approve</button>
                                <button type="submit" name="cancel" value="<?= $req['req_id']; ?>">Cancel</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </form>

    <?php include "scripts.php"; ?>
</body>
</html>