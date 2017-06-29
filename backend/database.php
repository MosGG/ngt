<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include 'database_config.php';

// SET TIME
$interval = "30";
$str = "-".$interval." day";
$date_current = date("Y/m/d");
$date = strtotime(date("Y-m-d", strtotime($str)));
$date_before = date("Y-m-d", $date);


// GET NEW ARRIVAL PAGEID
$sql = "SELECT pageId FROM newg_page where pageURL ='coming-soon';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pageid = $row['pageId'];
    }
} else {
    echo "no results";
}

// INSERT NEW ARRIVAL
$sql = "INSERT INTO newg_product_link (`product-linkProduct`,`product-linkPage`) ";
$sql .= "SELECT productId,".$pageid." FROM newg_product WHERE productDateCreate = '".$date_current."'";
$sql .= " and productId not in (select `product-linkProduct` from newg_product_link where `product-linkPage`= ".$pageid.")";
$result = $conn->query($sql);
if ($conn->query($sql) === TRUE) {
    echo "New product added successfully";
    echo $conn->affected_rows;
} else {
    echo "Error added record: " . $conn->error;
}

// REMOVE 30 DAYS AGO ARRIVAL
$sql = "DELETE FROM newg_product_link WHERE `product-linkPage` = ".$pageid;
$sql .= " AND `product-linkProduct` IN (SELECT `productId` FROM newg_hosting.newg_product where `productDateCreate` = '".$date_before."')";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
