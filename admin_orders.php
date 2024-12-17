<?php
// Start the session
session_start();

// Include the database configuration file
require_once 'config.php';

// // Check if the admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     // Redirect to admin login page
//     header('Location: admin_login.php');
//     exit();
// }

// Fetch orders from the database
$sql = "SELECT 
            o.id AS order_id,
            u.fullname AS user_name,
            GROUP_CONCAT(CONCAT(p.name, ' x', oi.quantity) SEPARATOR ', ') AS products,
            o.status,
            o.total_amount,
            DATE_FORMAT(o.order_date, '%Y-%m-%d') AS order_date
        FROM orders o
        INNER JOIN users u ON o.user_id = u.id
        INNER JOIN order_items oi ON o.id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.id
        GROUP BY o.id
        ORDER BY o.order_date DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Orders - Clothing Store</title>
    <!-- Link to existing stylesheets -->
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/admin_order.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Add any additional CSS styles for status badges -->
    <style>
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.Pending {
            background-color: orange;
        }

        .status.Delivered {
            background-color: green;
        }

        .status.Cancelled {
            background-color: red;
        }

        /* Additional status styles can be added here */
    </style>
</head>

<body>
    <!-- Header -->
    <header class="top-nav" id="admin-header">
        <div class="container">
            <div class="welcome">
                <span id="currentDateTime"></span>
            </div>

            <script>
                function updateDateTime() {
                    const now = new Date();
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    };
                    document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
                }

                updateDateTime();
                setInterval(updateDateTime, 1000); // Update every second
            </script>

            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-bell"></i></a></li>
                <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </header>

    <nav class="navigation">
        <div class="nav-center container d-flex">
            <a href="admin_dashboard.php" class="logo">Clothing Store Admin</a>
            <ul class="nav-list d-flex">
                <li class="nav-item">
                    <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="admin_products.php" class="nav-link">Products</a>
                </li>
                <li class="nav-item">
                    <a href="admin_orders.php" class="nav-link active">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="admin_users.php" class="nav-link">Users</a>
                </li>
                <li class="nav-item">
                    <a href="admin_report.php" class="nav-link">Reports</a>
                </li>
                <li class="nav-item">
                    <a href="admin_settings.php" class="nav-link">Settings</a>
                </li>
            </ul>
            <!-- Hamburger Menu for Mobile -->
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="section main-admin">
        <div class="container">
            <div class="title">
                <h1>Orders</h1>
            </div>

            <!-- Orders Table -->
            <div class="orders-table">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td data-label='Order ID'>#" . htmlspecialchars($row['order_id']) . "</td>
                                    <td data-label='User'>" . htmlspecialchars($row['user_name']) . "</td>
                                    <td data-label='Product'>" . htmlspecialchars($row['products']) . "</td>
                                    <td data-label='Status'><span class='status " . htmlspecialchars($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>
                                    <td data-label='Total'>$" . number_format($row['total_amount'], 2) . "</td>
                                    <td data-label='Date'>" . htmlspecialchars($row['order_date']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No orders found.</td></tr>";
                        }
                        // Free result set
                        mysqli_free_result($result);
                        // Close the database connection
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script>
        // Add JavaScript here if needed
    </script>
</body>

</html>