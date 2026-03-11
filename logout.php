<?php
session_start();
// 1. Include the centralized connection
include 'db_config.php'; 

// 2. UPDATE DATABASE: If a doctor is logging out, set them 'Offline'
if(isset($_SESSION['doctor_id'])) {
    $id = $_SESSION['doctor_id'];
    
    // Using a prepared statement for security
    $stmt = $conn->prepare("UPDATE doctors SET status='Offline' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// 3. CLEAR SESSION: Completely remove all data
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out | HealthConnect</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logout-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #28a745; /* Matches your doctor portal green */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        h2 { color: #333; margin: 10px 0; }
        p { color: #666; font-size: 15px; line-height: 1.5; }
    </style>
</head>
<body>

    <div class="logout-card">
        <div class="spinner"></div>
        <h2>Logging Out...</h2>
        <p>Your session is being cleared and status set to <strong>Offline</strong>.<br>Redirecting shortly.</p>
    </div>

    <script>
        // Redirect back to the landing page after 2 seconds
        setTimeout(function() {
            window.location.href = "index.html";
        }, 2000);
    </script>

</body>
</html>