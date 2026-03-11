<?php
session_start();
// 1. Include centralized DB config (to use your new MSI password)
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent basic SQL injection
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // 2. Use Prepared Statements (Professional/Secure way)
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 3. Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // 4. Set session variables required by user_dashboard.php
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Redirect to the Patient Dashboard
            header("Location: user_dashboard.php");
            exit(); 
        } else {
            echo "<script>alert('Invalid Password'); window.location.href='user_login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='user_login.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>