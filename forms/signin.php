<?php
session_start();
require_once "../conn.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve inputs directly from the $_POST array
    $email = isset($_POST['spree_user']['email']) ? filter_var($_POST['spree_user']['email'], FILTER_SANITIZE_EMAIL) : null;
    $password = isset($_POST['spree_user']['password']) ? $_POST['spree_user']['password'] : null;

    if (!$email || !$password) {
        echo "Both email and password are required.";
        exit();
    }

    // Check if the user exists in the database
    $query = "SELECT id, email, password_hash FROM applications WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Store user data in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the payment page
            header("Location: payment.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>