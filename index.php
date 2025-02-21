<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test1";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT customer_id, COUNT(order_id) AS total_orders, SUM(total_amount) AS total_spent
        FROM orders
        WHERE status != 'canceled' 
              AND order_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY customer_id
        ORDER BY total_orders DESC
        LIMIT 5";


$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

echo "<h2>Top 5 Customers (Last 3 Months)</h2>";
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Customer ID</th><th>Total Orders</th><th>Total Spent</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["customer_id"] . "</td><td>" . $row["total_orders"] . "</td><td>" . $row["total_spent"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No results found";
}

$conn->close();
?>
