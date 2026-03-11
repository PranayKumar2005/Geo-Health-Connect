<?php
session_start();
// 1. Include centralized DB config
include 'db_config.php'; 

// 2. Update status to 'Offline' before destroying the session
if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
    
    // Using a prepared statement for security
    $stmt = $conn->prepare("UPDATE doctors SET status = 'Offline' WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();
}

// 3. Clear and destroy the session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// 4. Feedback and Redirect
echo "<script>
        alert('Logged out successfully. Your clinic is now showing as Offline.');
        window.location.href='index.php';
      </script>";
exit();
?>