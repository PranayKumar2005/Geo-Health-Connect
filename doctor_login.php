<?php
session_start();
include 'db_config.php'; // 1. Use centralized connection (with your new password)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // 2. Search for the doctor using a Prepared Statement (Secure)
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();
        
        // 3. Verify the hashed password
        if (password_verify($password, $doctor['password'])) {
            
            // 4. Set session variables used by the dashboard
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['name'];
            $_SESSION['doctor_email'] = $doctor['email']; // Critical for the dashboard
            
            // 5. Automatically update status to Online upon login
            $doctor_id = $doctor['id'];
            $conn->query("UPDATE doctors SET status='Online' WHERE id=$doctor_id");
            
            // Redirect to the doctor dashboard
            header("Location: doctor_dashboard.php");
            exit(); 
        } else {
            echo "<script>alert('Incorrect Password'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Doctor account not found'); window.history.back();</script>";
    }
}
$conn->close();
?>