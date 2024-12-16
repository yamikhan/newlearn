<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register.html');
    exit();
}

// Include database connection
include '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];
    $payment_method = $_POST['payment_method'];

    // Validate required fields
    if (empty($course_id) || empty($payment_method)) {
        echo "Missing required payment details.";
        exit();
    }

    // Fetch the course price from the database
    $sql = "SELECT price FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "Invalid course ID.";
        exit();
    }
    
    $course = $result->fetch_assoc();
    $amount = $course['price']; // Set the course price as the payment amount
    $stmt->close();

    // Initialize variables for additional payment details
    $paypal_email = null;
    $card_number = null;
    $card_expiry = null;
    $card_cvc = null;
    $skrill_email = null;

    // Handle additional payment method details
    if ($payment_method === 'PayPal') {
        $paypal_email = $_POST['paypal_email'] ?? '';
        if (empty($paypal_email) || !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid PayPal email address.";
            exit();
        }
    } elseif ($payment_method === 'Credit Card') {
        $card_number = $_POST['card_number'] ?? '';
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvc = $_POST['card_cvc'] ?? '';

        if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) {
            echo "Invalid credit card number.";
            exit();
        }

        if (empty($card_expiry) || !preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $card_expiry)) {
            echo "Invalid card expiry date. Use MM/YY format.";
            exit();
        }

        if (empty($card_cvc) || !preg_match('/^\d{3,4}$/', $card_cvc)) {
            echo "Invalid CVC code.";
            exit();
        }
    } elseif ($payment_method === 'Skrill') {
        $skrill_email = $_POST['skrill_email'] ?? '';
        if (empty($skrill_email) || !filter_var($skrill_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid Skrill email address.";
            exit();
        }
    } else {
        echo "Invalid payment method.";
        exit();
    }

    // Insert payment details into the database
    $sql = "INSERT INTO payments (user_id, course_id, payment_method, amount, paypal_email, card_number, card_expiry, card_cvc, skrill_email, payment_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            'iisssssss',
            $user_id,
            $course_id,
            $payment_method,
            $amount,
            $paypal_email,
            $card_number,
            $card_expiry,
            $card_cvc,
            $skrill_email
        );

        if ($stmt->execute()) {
            echo "Payment successful!";
        } else {
            echo "Error processing payment: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
