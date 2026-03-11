<?php
// 1. Include centralized DB config (using your new MSI password)
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Get and sanitize data from form
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    // Securely hash the password
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 3. SECURE CHECK: Use prepared statements to check if email exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        // If email exists, show alert and stay on registration page
        echo "<script>
                alert('This email is already registered! Please use a different one or login.');
                window.location.href='user_registration.html';
              </script>";
    } else {
        // 4. If email is new, insert it securely
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $pass);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Patient Registered Successfully!'); 
                    window.location.href='user_login.html';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkEmail->close();
}
$conn->close();
?>