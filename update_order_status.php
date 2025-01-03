<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Include the database configuration file
require_once 'config.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_id'], $_POST['status'])) {
        // Retrieve and sanitize form data
        $order_id = intval($_POST['order_id']);
        $new_status = $_POST['status'];

        // Define allowed statuses
        $allowed_statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Returned'];

        // Validate the new status
        if (in_array($new_status, $allowed_statuses)) {
            // Prepare and execute the update query
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param('si', $new_status, $order_id);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Order #$order_id status updated to '$new_status'.";
            } else {
                $_SESSION['error_message'] = "Error updating order status.";
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Invalid status selected.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid form data submitted.";
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
}

// Redirect back to the orders page
header("Location: admin_orders.php");
exit();
?>