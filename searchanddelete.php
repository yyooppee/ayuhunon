<?php include "component/db.php"; ?>

<html>
<head>
    <?php include "component/head.php"; ?>
</head>
<body>
    <?php include "component/header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
            <p>Mahayahay, Gabi, Cordova</p>
            <p>mimispetcorner@gmail.com</p>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <h1>Search Invoice</h1>
            <form method="post" action="">
                <label for="invoice_id">Enter Invoice ID:</label>
                <input type="text" id="invoice_id" name="invoice_id" required>
                <input type="submit" value="Search" name="search">
            </form>

            <?php 
            if(isset($_POST["search"])){
                $invoiceId = mysqli_real_escape_string($conn, $_POST["invoice_id"]);
                $sql = "SELECT * FROM invoice WHERE inv_id = '$invoiceId'";
                $result = $conn->query($sql);
            
                if($result && $result->num_rows > 0){
                    $invoiceData = $result->fetch_assoc();
            
                    // Display the information in a table
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Invoice Number</th>
                                        <th>Total Amount</th>
                                        <th>Date of Invoice</th>
                                        <th>Employee ID</th>
                                        <th>Delete?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{$invoiceData['inv_id']}</td>
                                        <td>{$invoiceData['inv_amnt']}</td>
                                        <td>{$invoiceData['inv_date']}</td>
                                        <td>{$invoiceData['emp_id']}</td>
                                        <td>
                                            <form method='post' action=''>
                                                <input type='hidden' name='delete_id' value='{$invoiceData['inv_id']}'>
                                                <input type='submit' name='delete' value='Delete'>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>";
                } else {
                    echo "<p>No invoice found with the specified ID.</p>";
                }
            }
            
            if(isset($_POST["delete"])){
                $delete_id = $_POST["delete_id"];
                $delete_sql = "DELETE FROM invoice WHERE inv_id = '$delete_id'";
                $result_delete = mysqli_query($conn, $delete_sql);

                if($result_delete) {
                    echo "<script>alert('Invoice deleted successfully')</script>";
                } else {
                    echo "<script>alert('Failed to delete invoice')</script>";
                }
            }
            ?>
        </div>
    </div>

    <div class="content">
        <div class="container">  
            <?php
            if(isset($_POST["view"])){
                $invoiceId = mysqli_real_escape_string($conn, $_POST["inv_id"]);
                $sql = "SELECT * FROM invoice WHERE inv_id = '$invoiceId'";
                $result = $conn->query($sql);
            
                $view_inv_id = $_POST["inv_id"];
                $view_sql = "SELECT orderlist.inv_id, order_id, prod_id, order_name, order_qty, order_price, inv_amnt, inv_date
                FROM orderlist
                INNER JOIN invoice
                ON orderlist.inv_id = invoice.inv_id
                WHERE orderlist.inv_id = '$view_inv_id'";
                $result_view = $conn->query($view_sql);
                if($result && $result->num_rows > 0){
                    $invoiceData = $result->fetch_assoc();
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Invoice Number</th>
                                        <th>Total Amount</th>
                                        <th>Date of Invoice</th>
                                        <th>Employee ID</th>
                                        <th>Delete?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{$invoiceData['inv_id']}</td>
                                        <td>{$invoiceData['inv_amnt']}</td>
                                        <td>{$invoiceData['inv_date']}</td>
                                        <td>{$invoiceData['emp_id']}</td>
                                        <td>
                                            <form method='post' action=''>
                                                <input type='hidden' name='delete_id' value='{$invoiceData['inv_id']}'>
                                                <input type='submit' name='delete' value='Delete'>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>";
                }

                if ($result_view && $result_view->num_rows > 0) {
                    
                    echo "<div class='container'>  
                            <table class='table table-bordered text-center'>
                                <thead>
                                    <tr class='bg-dark text-white'>
                                        <th>Order number</th>
                                        <th>Order Name</th>
                                        <th>Order quantity</th>
                                        <th>Order Price</th>
                                        <th>Date of Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>";

                    foreach ($result_view as $row) {
                        echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['order_name']}</td>
                                <td>{$row['order_qty']}</td>
                                <td>{$row['order_price']}</td>
                                <td>{$row['inv_date']}</td>
                              </tr>";
                    }

                    echo "</tbody>
                          </table>
                          </div>";
                } else {
                    echo "<p>No order list found for the specified invoice ID.</p>";
                }
            }
            ?>
            <form method="post" action="">
                <input type="hidden" name="inv_id" value="<?php echo isset($invoiceData['inv_id']) ? $invoiceData['inv_id'] : ''; ?>">
                <input type="submit" value="view invoice" name="view">
            </form>
        </div>
    </div>

    <?php include "component/scripts.php"; ?>
</body>
</html>
