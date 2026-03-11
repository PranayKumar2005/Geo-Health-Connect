<?php
// 1. Include the centralized configuration (which has your new MSI password)
include 'db_config.php';

// 2. Capture Form Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs to protect your database
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $hospital = $conn->real_escape_string($_POST['hospital']);
    
    // This captures the manual text entry (e.g., 'Ghatkesar')
    $location = $conn->real_escape_string($_POST['location']); 
    
    // Note: Changed 'specialization' to 'spec' to match your HTML input name
    $specialization = $conn->real_escape_string($_POST['spec']);
    
    // Secure password hashing
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 3. Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM doctors WHERE email=?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit();
    }

    // 4. Insert Data into Database
    // Note: status is set to 'Offline' by default to prevent "Ghost Clinic" false presence
    $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, hospital, location, specialization, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Offline')");
    $stmt->bind_param("sssssss", $name, $email, $phone, $hospital, $location, $specialization, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful for $location clinic!'); window.location.href='doctor_login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>