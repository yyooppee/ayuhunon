<?php include "component/db.php"; 
include "component/functions.php";
?>

<?php 
$sql = "SELECT * FROM orderlist WHERE order_stat = 'pending';";
$result = $conn->query($sql);
?>

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
            <h2> Invoice Id </h2>
            <?php
            $sql_invoice = "SELECT inv_id FROM invoice WHERE emp_id = 1 AND inv_status = 'open'";
            $result_invoice = mysqli_query($conn, $sql_invoice);

            if ($row_invoice = mysqli_fetch_assoc($result_invoice)) {
                // If an open invoice exists, use its ID
                $invoice_id = $row_invoice['inv_id'];
                ?>
                <h4><?php echo $invoice_id; ?></h4>
                <?php
            }
            ?>

</div>
</div>

    <div class="content">
        <div class="container">  
            <h2> Cart </h2>
            <form method="post" action="">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>order_id</th>
                            <th>prod_id</th>
                            <th>order_name</th>
                            <th>prod_desc</th>
                            <th>order_qty</th>
                            <th>order_price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Fetch all rows into an array
                        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        $total_amount = 0;

                        foreach($rows as $row){
                            $total_amount += $row['order_price']; // Accumulate total amount
                        ?>
                        <tr>
                            <td><?php echo $row['order_id']?></td>
                            <td><?php echo $row['prod_id']?></td>
                            <td><?php echo $row['order_name']?></td>
                            <td><?php echo $row['prod_desc']?></td>
                            <td><?php echo $row['order_qty']?></td>
                            <td><?php echo $row['order_price']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <p>Total Amount: <?php echo $total_amount; ?></p>
                <input type="submit" value="Purchase" name="buy">
                <input type="button" value="Edit Cart" onclick="window.location.href='editcart.php';">
            </form>

            <?php
            // Handle the Purchase button logic
            if(isset($_POST["buy"])){
                // Assuming emp_id is hardcoded as 1
                $emp_id = 1;

                // Update order statuses to 'completed'
                $sql_update_orders = "UPDATE orderlist SET order_stat = 'completed' WHERE order_stat = 'pending'";
                $result_update_orders = mysqli_query($conn, $sql_update_orders);

                if($result_update_orders){
                    // Update the total amount in the invoice table
                    $sql_update_invoice = "UPDATE invoice SET inv_amnt = $total_amount, inv_status = 'closed' WHERE emp_id = $emp_id AND inv_status = 'open'";
                    $result_update_invoice = mysqli_query($conn, $sql_update_invoice);



                    if($result_update_invoice){
                        $invoice_id = $row_invoice['inv_id'];
                        generatePDFReceipt($rows, $total_amount, $invoice_id);
                        echo "<script>alert('Purchase successful.')</script>";
                    } else {
                        echo "<script>alert('Error updating invoice: " . mysqli_error($conn) . "')</script>";
                    }
                } else {
                    echo "<script>alert('Error updating orders: " . mysqli_error($conn) . "')</script>";
                }
            }
            ?>
            <?php 
            
            ?>
        </div>
    </div>

    <?php include "component/scripts.php"; ?>
</body>
</html>